<?php

namespace Core;

use Conf\Config;

class TemplateInheritenceResolver
{
	private $opening_delimiter = "{{";
	private $closing_delimiter = "}}";
	private $inheritence_map = [];
	private $target_file;
	private $templates_dir = "App/Views/Templates/";
	private $tmp_dir = "App/Views/tmp/";

	public function buildTemplate( $target_file )
	{
		// Set the target file as the filename provided
		$this->target_file = $target_file;

		// Parse the target file for tags
		$tags = $this->parseTemplate( $this->target_file );

		// If the target file has no parent tag, return the filename
		if ( !is_null( $tags[ "parent" ] ) ) {

			// Add the tags for the target file to the inheritence map
			$this->inheritence_map[ $this->target_file ] = $tags;

			// Recursively find parent templates and parse out the content
			$this->resolveInheritence( $tags[ "parent" ] );

			// Replace all of the tags in each template with respective content
			$file_contents = $this->generateFileContents();
			
			// Create the temp file
			$temp_filename = uniqid( rand(), true ) . ".php";
			$temp_file = fopen( $this->tmp_dir . $temp_filename, "w" );

			// Write all resolved content to the tempfile and close
			fwrite( $temp_file, $file_contents );
			fclose( $temp_file );
			
			$this->setTempFile( $this->tmp_dir . $temp_filename );

			return true;
		}
		
		return false;
	}

	// Creates a list of parent files by following each parent tag until none is found
	private function resolveInheritence( $filename )
	{
		// If a parent tag is found, add the parent filename to the list and
		// countiue searching in the parent files for more parent files 
		$tags = $this->buildParent( $filename );

		// If no parent tag is set, Inheritence is resolved
		if ( !is_null( $tags[ "parent" ] ) ) {
			return $this->resolveInheritence( $tags[ "parent" ] );
		}

		return;
	}

	private function buildParent( $filename )
	{
		// Make sure a filename is part of the tag
		$this->validateTemplateFilename( $filename );

		// Throw error if target template recursion detected
		if ( in_array( $this->target_file, $this->inheritence_map ) || $this->target_file == $filename ) {
			throw new \Exception( "Target template recursion detected in file {$filename}" );
		}
		
		// Throw error if parent template recursion detected
		if ( in_array( $filename, $this->inheritence_map ) ) {
			throw new \Exception( "Parent template recursion detected between files {$filename} - " . implode( ", ", $this->inheritence_map ) );
		}

		// Get all the tags in the parent file
		$tags = $this->parseTemplate( $filename );
		
		// Add the parent file name to the list of parent files.
		$this->inheritence_map[ $filename ] = $tags;

		// Return the parents filename
		return $tags;
	}

	private function parseTemplate( $filename )
	{
		// Check to see if the file exists
		$this->validateTemplateFilename( $filename );

		// Get the file 
		$file_contents = file_get_contents( $this->templates_dir . $filename );

		// Collect all the tags in an array and return it
		$tags = [];

		$tags[ "parent" ] = $this->parseParentTags( $file_contents );
		$tags[ "blocks" ] = $this->parseBlockTags( $file_contents );
		$tags[ "parentblocks" ] = $this->parseParentblockTags( $file_contents );

		// If the parent tag is null, it can be assumed that this file is the final parent file
		if ( is_null( $tags[ "parent" ] ) ) {
			$tags[ "content" ] = $file_contents;
		}
		
		return $tags;
	}

	private function generateFileContents()
	{
		$last_child = null;
		$compiled_blocks = null;
		foreach ( $this->inheritence_map as $child_filename => &$child_components ) {
			// If the parent is null, that there is no more templates from which to
			// inherit and therefore, the last element in the array
			if ( is_null( $child_components[ "parent" ] ) ) {
				break;
			}

			foreach ( $child_components[ "blocks" ] as $child_parentblock_name => $child_contents ) {
				foreach ( $this->inheritence_map[ $child_components[ "parent" ] ][ "blocks" ] as $parent_parentblock_name => $parent_contents ) {
					$new_parent_contents = str_replace(
						"{{parentblock " . $child_parentblock_name . "}}",
						$child_contents,
						$this->inheritence_map[ $child_components[ "parent" ] ][ "blocks" ][ $parent_parentblock_name ]
					);
					
					$this->inheritence_map[ $child_components[ "parent" ] ][ "blocks" ][ $parent_parentblock_name ] = $new_parent_contents;
				}
			}

			$last_child = $this->inheritence_map[ $child_filename ];
		}

		$file_contents = $this->inheritence_map[ $last_child[ "parent" ] ][ "content" ];
		foreach ( $last_child[ "blocks" ] as $child_parentblock_name => $child_contents ) {
			$file_contents = str_replace(
				"{{parentblock " . $child_parentblock_name . "}}",
				$child_contents,
				$file_contents
			);

		}
					
		return $file_contents;
	}

	// Returns a list of tags and contents within the tags in a provided string
	private function parseBlockTags( $file_contents )
	{
		preg_match_all( "/\{\{block\s(?<blocks>[\w]+)\}\}(?<content>[\S\s]*?)\{\{\/block\}\}/", $file_contents, $matches );

		// Clean up. Remove all unnamed keys.
		foreach ( $matches as $key => $value ) {
	        if ( is_int( $key ) ) {
	            unset( $matches[ $key ] );
	        }
	    }

	    // Make an array of the matched blocks with the block name as the key and the content
	    // as the value
	    $blocks = [];
	    foreach ( $matches[ "blocks" ] as $key => $value ) {
	    	$blocks[ $value ] = $matches[ "content" ][ $key ];
	    }

		return $blocks;
	}

	private function parseParentblockTags( $file_contents )
	{
		preg_match_all( "/\{\{parentblock\s(?<blocknames>[\w]*)\}\}/", $file_contents, $matches );

		// Clean up. Remove all unnamed keys.
		foreach ( $matches as $key => $value ) {
	        if ( is_int( $key ) ) {
	            unset( $matches[ $key ] );
	        }
	    }

		return $matches[ "blocknames" ];
	}

	private function parseParentTags( $file_contents )
	{
		preg_match( "/\{\{parent\s(?<filename>[a-zA-Z0-9\/.]*)\}\}/", $file_contents, $matches );

		if ( !empty( $matches ) ) {
			if ( $matches[ "filename" ] != "" ) {
				return $matches[ "filename" ];
			}

			throw new \Exception( "File component of Parent Tag cannot be empty" );
		}

		return null;
	}

	private function formatTag( $tag )
	{
		return preg_replace( "/\s+/", " ", $tag );
	}

	private function stripDelimiters( $tag )
	{
		return str_replace( $this->opening_delimiter, "", str_replace( $this->closing_delimiter, "", $tag ) );
	}
	
	private function validateTemplateFilename( $filename )
	{
		if ( !is_file( $this->templates_dir . $filename ) ) {
			throw new \Exception( "Invalid Template File: '{$this->templates_dir}{$filename}' does not exist." );
		}

		return;
	}

	public function setDelimiters( $opening_delimiter, $closing_delimiter )
	{
		$this->opening_delimiter = preg_quote( $opening_delimiter );
		$this->closing_delimiter = preg_quote( $closing_delimiter );

		return $this;
	}

	// Remove the parentblock tags from file contents 
	private function stripParentblockTags( $file_contents )
	{
		return preg_replace( "/\{\{parentblock [\w]*\}\}/", "", $file_contents );
	}

	private function setTempFile( $filename )
	{
		$this->temp_file = $filename;

		return $this;
	}

	public function getTempFile()
	{
		if ( isset( $this->temp_file ) === false ) {
			throw new \Exception( "temp_file not set" );
		}

		return $this->temp_file;
	}

	public function removeTempFile()
	{
		$filename = $this->getTempFile();

		if ( is_file( $this->tmp_dir . basename( $filename ) ) ) {
			unlink( $this->tmp_dir . basename( $filename ) );
		}

		return;
	}
}