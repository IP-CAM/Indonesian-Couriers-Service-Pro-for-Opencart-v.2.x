<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-shindo" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-weight" class="form-horizontal">
          <div class="row">
            <div class="col-sm-2">
              <?php $mod = array('igsjne', 'igstiki');?>
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                <?php foreach ($mod as $m) {?>
                  <li><a href="#tab-<?php echo $m;?>" data-toggle="tab"><?php echo ${'tab_' . $m}; ?></a></li>
                <?php } ?>
              </ul>
            </div>
            <div class="col-sm-10">
              <div class="tab-content">
                <div class="tab-pane active" id="tab-general">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-shindo-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="shindo_status" id="input-shindo-status" class="form-control">
                        <?php if ($shindo_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-apikey"><?php echo $entry_apikey; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="shindo_apikey" value="<?php echo $shindo_apikey; ?>" placeholder="<?php echo $entry_apikey; ?>" id="input-apikey" class="form-control" />
                        <?php if ($error_apikey) { ?>
                        <div class="text-danger"><?php echo $error_apikey; ?></div>
                        <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-province"><?php echo $entry_province; ?></label>
                    <div class="col-sm-10">
                      <select name="shindo_province_id" id="input-province" class="form-control">
                        <option value=""><?php echo $text_select;?></option>
                        <?php foreach ($provinces['rajaongkir']['results'] as $province) { ?>
                        <?php if ($province['province_id'] == $shindo_province_id) { ?>
                        <option value="<?php echo $province['province_id']; ?>" selected="selected"><?php echo $province['province']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $province['province_id']; ?>"><?php echo $province['province']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                      <?php if ($error_province_id) { ?>
                      <div class="text-danger"><?php echo $error_province_id; ?></div>
                      <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-city"><?php echo $entry_city; ?></label>
                  <div class="col-sm-10">
                    <select name="shindo_city_id" id="input-city" class="form-control">
                    </select>
                    <?php if ($error_city_id) { ?>
                    <div class="text-danger"><?php echo $error_city_id; ?></div>
                    <?php } ?>
                  </div>
                </div>

              </div>

              <?php foreach ($mod as $m) {?>
              <div class="tab-pane" id="tab-<?php echo $m;?>">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-<?php echo $m;?>-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="<?php echo $m;?>_status" id="input-<?php echo $m;?>-status" class="form-control">
                      <?php if (${$m .'_status'}) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-<?php echo $m;?>-handling"><?php echo $entry_handling; ?></label>
                  <div class="col-sm-10">
                      <input size="10" type="text" name="<?php echo $m;?>_handling" value="<?php echo ${$m .'_handling'};?>" placeholder="<?php echo $entry_handling; ?>" id="input-<?php echo $m;?>-handling" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_handlingmode; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if (${$m . '_handlingmode'}==1) { ?>
                      <input type="radio" name="<?php echo $m;?>_handlingmode" value="1" checked="checked" />
                      <?php echo $option_handlingmode1; ?>
                      <?php } else { ?>
                      <input type="radio" name="<?php echo $m;?>_handlingmode" value="1" />
                      <?php echo $option_handlingmode1; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (${$m . '_handlingmode'}==2) { ?>
                      <input type="radio" name="<?php echo $m;?>_handlingmode" value="2" checked="checked" />
                      <?php echo $option_handlingmode2; ?>
                      <?php } else { ?>
                      <input type="radio" name="<?php echo $m;?>_handlingmode" value="2" />
                      <?php echo $option_handlingmode2; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-<?php echo $m;?>-weight-class"><span data-toggle="tooltip" title="<?php echo $help_weight_class; ?>"><?php echo $entry_weight_class; ?></span></label>
                  <div class="col-sm-10">
                    <select name="<?php echo $m;?>_weight_class_id" id="input-<?php echo $m;?>-weight-class" class="form-control">
                      <?php foreach ($weight_classes as $weight_class) { ?>
                      <?php if ($weight_class['weight_class_id'] == ${$m . '_weight_class_id'}) { ?>
                      <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-<?php echo $m;?>-tax-class"><?php echo $entry_tax_class; ?></label>
                  <div class="col-sm-10">
                    <select name="<?php echo $m;?>_tax_class_id" id="input-<?php echo $m;?>-tax-class" class="form-control">
                      <option value="0"><?php echo $text_none; ?></option>
                      <?php foreach ($tax_classes as $tax_class) { ?>
                      <?php if ($tax_class['tax_class_id'] == ${$m . '_tax_class_id'}) { ?>
                      <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-<?php echo $m;?>-geo-zone"><?php echo $entry_geo_zone; ?></label>
                  <div class="col-sm-10">
                    <select name="<?php echo $m;?>_geo_zone_id" id="input-<?php echo $m;?>-geo-zone" class="form-control">
                      <option value="0"><?php echo $text_all_zones; ?></option>
                      <?php foreach ($geo_zones as $geo_zone) { ?>
                      <?php if ($geo_zone['geo_zone_id'] == ${$m . '_geo_zone_id'}) { ?>
                      <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_service; ?></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                      <?php foreach (${$m . '_services'} as $service) { ?>
                      <div class="checkbox">
                        <label>
                          <?php if (in_array($service['value'], ${$m . '_service'})) { ?>
                          <input type="checkbox" name="<?php echo $m; ?>_service[]" value="<?php echo $service['value']; ?>" checked="checked" />
                          <?php echo $service['value']; ?>
                          <?php } else { ?>
                          <input type="checkbox" name="<?php echo $m; ?>_service[]" value="<?php echo $service['value']; ?>" />
                          <?php echo $service['value']; ?>
                          <?php } ?>
                        </label>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-<?php echo $m;?>-sort-order"><?php echo $entry_sort_order; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="<?php echo $m;?>_sort_order" value="<?php echo ${$m .'_sort_order'};?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-<?php echo $m;?>-sort-order" class="form-control" />
                  </div>
                </div>

              </div>

              <?php } ?>
              </div>
              </div>
              </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'shindo_province_id\']').on('change', function() {
$.ajax({
  url: 'index.php?route=shipping/shindo/cities&token=<?php echo $token; ?>&province_id=' + this.value,
  dataType: 'json',
  beforeSend: function() {
    $('select[name=\'shindo_province_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
  },
  complete: function() {
    $('.fa-spin').remove();
  },
  success: function(json) {
    html = '<option value=""><?php echo $text_select; ?></option>';

    if (json && json != '') {
      for (i = 0; i < json['rajaongkir']['results'].length; i++) {
              html += '<option value="' + json['rajaongkir']['results'][i]['city_id'] + '"';

        if (json['rajaongkir']['results'][i]['city_id'] == '<?php echo $shindo_city_id; ?>') {
                html += ' selected="selected"';
        }

        html += '>' + json['rajaongkir']['results'][i]['city_name'] + ' - ' + json['rajaongkir']['results'][i]['type'] + '</option>';
      }
    } else {
      html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
    }

    $('select[name=\'shindo_city_id\']').html(html);

    $('#button-save').prop('disabled', false);
  },
  error: function(xhr, ajaxOptions, thrownError) {
    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  }
});
});

$('select[name=\'shindo_province_id\']').trigger('change');
//--></script>
</div>
<?php echo $footer; ?>
