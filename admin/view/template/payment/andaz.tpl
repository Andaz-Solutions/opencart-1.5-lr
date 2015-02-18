<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_client_id; ?></td>
                        <td><input type="text" name="andaz_client_id" value="<?php echo $andaz_client_id; ?>" />
                            <?php if ($error_client_id) { ?>
                            <span class="error"><?php echo $error_client_id; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_client_username; ?></td>
                        <td><input type="text" name="andaz_client_username" value="<?php echo $andaz_client_username; ?>" />
                            <?php if ($error_client_username) { ?>
                            <span class="error"><?php echo $error_client_username; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_client_password; ?></td>
                        <td><input type="text" name="andaz_client_password" value="<?php echo $andaz_client_password; ?>" />
                            <?php if ($error_client_password) { ?>
                            <span class="error"><?php echo $error_client_password; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_client_token; ?></td>
                        <td><input type="text" name="andaz_client_token" value="<?php echo $andaz_client_token; ?>" />
                            <?php if ($error_client_token) { ?>
                            <span class="error"><?php echo $error_client_token; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_geo_zone; ?></td>
                        <td><select name="andaz_geo_zone_id">
                                <option value="0"><?php echo $text_all_zones; ?></option>
                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                <?php if ($geo_zone['geo_zone_id'] == $andaz_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_transaction; ?></td>
                        <td><select name="andaz_method">
                                <?php if ($andaz_method == 'authorize') { ?>
                                <option value="authorize" selected="selected"><?php echo $text_authorization; ?></option>
                                <?php } else { ?>
                                <option value="authorize"><?php echo $text_authorization; ?></option>
                                <?php } ?>
                                <?php if ($andaz_method == 'capture') { ?>
                                <option value="capture" selected="selected"><?php echo $text_sale; ?></option>
                                <?php } else { ?>
                                <option value="capture"><?php echo $text_sale; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_total; ?></td>
                        <td><input type="text" name="andaz_total" value="<?php echo $andaz_total; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_order_status; ?></td>
                        <td><select name="andaz_order_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $andaz_order_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="andaz_status">
                                <?php if ($andaz_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="andaz_sort_order" value="<?php echo $andaz_sort_order; ?>" size="1" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>
