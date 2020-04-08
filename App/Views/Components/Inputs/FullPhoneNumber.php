<?php if ( !is_null( $this->getData( "countries" ) ) ): ?>
<div class="mt10">
    <div class="w40 fl pr10">
        <div class="">
            <p class="label">Code</p>
            <select name="country_code" required="required" class="inp">
                <option selected="selected" value="1">+1 USA</option>
                <?php
                    $countries = $this->getData( "countries" );
                    foreach( $countries as $country ):
                ?>
                <option value="<?=$country->phonecode?>">+ <?=$country->phonecode?> <?php echo( ( !empty( $country->iso3 ) ? $country->iso3 : $country->iso ) ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="w60 fl">
        <div>
            <p class="label">Phone Number</p>
            <input type="tel" name="national_number" value="<?php $this->echoData( "national_number" ); ?>" required="required" placeholder="10 digit #" class="inp" />
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php else: ?>
<?php $this->renderErrorMessage( "Component Error: 'countries' is not set" ); ?>
<?php endif; ?>