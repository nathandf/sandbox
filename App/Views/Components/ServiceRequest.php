<?php if ( in_array( $serviceRequest->status, [ "pending", "dispatched" ] ) ): ?>
<div class="service-request">
	<div class="p10">
		<p>We're searching for someone to contact you right now.</p>
		<p>If this is an emergency, please dial 911</p>
		<p class="mt10">Revieved @ <?=$serviceRequest->created_at?></p>
	</div>
	<hr>
	<div class="pt10">
		<form action="<?=HOME?>service-request/<?=$serviceRequest->id?>/cancel" method="post">
			<input type="hidden" name="csrf_token" value="<?php $this->getData( "csrf_token" ); ?>">
			<input type="hidden" name="cancel" value="1">
			<button type="submit" class="button bg-red c-white --confirm" data-confirm_message="Are you sure you want to cancel this request?">Cancel Request</button>
		</form>
	</div>
</div>
<?php endif; ?>