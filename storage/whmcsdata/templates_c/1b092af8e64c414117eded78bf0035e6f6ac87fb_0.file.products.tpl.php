<?php
/* Smarty version 3.1.36, created on 2023-10-22 23:45:23
  from '/home4/adxventure/hosting.adxventure.com/templates/orderforms/cloud_slider/products.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_6535b413601c09_04111941',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1b092af8e64c414117eded78bf0035e6f6ac87fb' => 
    array (
      0 => '/home4/adxventure/hosting.adxventure.com/templates/orderforms/cloud_slider/products.tpl',
      1 => 1679580036,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:orderforms/standard_cart/sidebar-categories.tpl' => 1,
    'file:orderforms/cloud_slider/recommendations-modal.tpl' => 1,
  ),
),false)) {
function content_6535b413601c09_04111941 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home4/adxventure/hosting.adxventure.com/vendor/smarty/smarty/libs/plugins/modifier.regex_replace.php','function'=>'smarty_modifier_regex_replace',),));
?>
<!--[if lt IE 9]>
<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"><?php echo '</script'; ?>
>
<![endif]-->

<!-- RangeSlider CSS -->
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['BASE_PATH_CSS']->value;?>
/ion.rangeSlider.css" property="stylesheet" />
<!-- RangeSlider CSS -->
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['BASE_PATH_CSS']->value;?>
/ion.rangeSlider.skinHTML5.css" property="stylesheet" />
<!-- Product Recommendations CSS -->
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['BASE_PATH_CSS']->value;?>
/recommendations.min.css" property="stylesheet" />
<!-- Core CSS -->
<link type="text/css" rel="stylesheet" href="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['assetPath'][0], array( array('file'=>"style.css"),$_smarty_tpl ) );?>
" property="stylesheet" />

<?php echo '<script'; ?>
>
jQuery(document).ready(function () {
    jQuery('#btnShowSidebar').click(function () {
        if (jQuery(".product-selection-sidebar").is(":visible")) {
            jQuery('.row-product-selection').css('left','0');
            jQuery('.product-selection-sidebar').fadeOut();
            jQuery('#btnShowSidebar').html('<i class="fas fa-arrow-circle-right"></i> <?php echo $_smarty_tpl->tpl_vars['LANG']->value['showMenu'];?>
');
        } else {
            jQuery('.product-selection-sidebar').fadeIn();
            jQuery('.row-product-selection').css('left','300px');
            jQuery('#btnShowSidebar').html('<i class="fas fa-arrow-circle-left"></i> <?php echo $_smarty_tpl->tpl_vars['LANG']->value['hideMenu'];?>
');
        }
    });
});
<?php echo '</script'; ?>
>

<?php if ($_smarty_tpl->tpl_vars['errormessage']->value) {?>
    <div class="alert alert-danger">
        <?php echo $_smarty_tpl->tpl_vars['errormessage']->value;?>

    </div>
<?php } else { ?>

    <div class="row row-product-selection">
        <div class="col-md-3 sidebar product-selection-sidebar" id="premiumComparisonSidebar">
            <?php $_smarty_tpl->_subTemplateRender("file:orderforms/standard_cart/sidebar-categories.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        </div>
        <div class="col-md-12">

            <div id="order-cloud_slider">
                <section class="plans-full-main">
                    <?php if ($_smarty_tpl->tpl_vars['showSidebarToggle']->value) {?>
                        <div class="pull-left float-left">
                            <button type="button" class="btn btn-default btn-sm" id="btnShowSidebar">
                                <i class="fas fa-arrow-circle-right"></i>
                                <?php echo $_smarty_tpl->tpl_vars['LANG']->value['showMenu'];?>

                            </button>
                        </div>
                    <?php }?>
                    <div class="main-container">
                        <div class="pg-cont-container">
                            <?php if (!$_smarty_tpl->tpl_vars['errormessage']->value && !$_smarty_tpl->tpl_vars['productGroup']->value) {?>
                                <div class="alert alert-info">
                                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'orderForm.selectCategory'),$_smarty_tpl ) );?>

                                </div>
                            <?php } else { ?>
                                <div class="heading-with-cloud">
                                    <div id="headline" class="texts-container">
                                        <?php if ($_smarty_tpl->tpl_vars['productGroup']->value['headline']) {?>
                                            <?php echo $_smarty_tpl->tpl_vars['productGroup']->value['headline'];?>

                                        <?php } else { ?>
                                            <?php echo $_smarty_tpl->tpl_vars['productGroup']->value['name'];?>

                                        <?php }?>
                                    </div>
                                    <div class="images-container">
                                        <img src="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['assetPath'][0], array( array('ns'=>"img",'file'=>"sky-hr.png"),$_smarty_tpl ) );?>
" alt="">
                                    </div>
                                </div>

                                <?php if ($_smarty_tpl->tpl_vars['productGroup']->value['tagline']) {?>
                                    <div id="tagline" class="tag-line-head">
                                        <h5 class="font-size-14"><?php echo $_smarty_tpl->tpl_vars['productGroup']->value['tagline'];?>
</h5>
                                    </div>
                                <?php }?>

                                <!-- Start: Price Calculation Box -->
                                <div class="price-calc-container">
                                    <div class="price-calc-top">
                                        <div class="row clearfix">
                                            <div class="col-md-9" id="products-top">
                                                <input type="hidden" id="scroll-top" name="scroll-top" value="" />
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <span id="priceTop" class="price-cont">--</span>
                                                <a href="#" class="order-btn" id="product-order-button">
                                                    <?php echo $_smarty_tpl->tpl_vars['LANG']->value['ordernowbutton'];?>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="price-calc-btm">

                                        <!-- Start: Progress Area Container -->
                                        <div id="productFeaturesTop" class="row clearfix">
                                            <!-- Javascript will populate this area with product features. -->
                                        </div>
                                        <!-- End: Progress Area Container -->

                                        <div id="productDescription"></div>

                                        <?php if (count($_smarty_tpl->tpl_vars['productGroup']->value['features']) > 0) {?>
                                            <!-- Start: Includes Container -->
                                            <div class="includes-container">
                                                <div class="row clearfix">

                                                    <div class="col-md-12">
                                                        <div class="head-area">
                                                        <span>
                                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['whatIsIncluded'];?>

                                                        </span>
                                                        </div>

                                                        <ul id="list-contents" class="list-contents">
                                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['productGroup']->value['features'], 'features');
$_smarty_tpl->tpl_vars['features']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['features']->value) {
$_smarty_tpl->tpl_vars['features']->do_else = false;
?>
                                                                <li><?php echo $_smarty_tpl->tpl_vars['features']->value['feature'];?>
</li>
                                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                        </ul>

                                                    </div>

                                                </div>
                                            </div>
                                            <!-- End: Includes Container -->
                                        <?php }?>
                                    </div>
                                </div>
                                <!-- End: Price Calculation Box -->
                            <?php }?>

                            <!-- Start: Features Content -->
                            <div class="price-features-container">
                                <div class="row clearfix">

                                    <!-- Start: Feature 01 -->
                                    <div class="col-md-12 feature-container clearfix">
                                        <div class="left-img">
                                            <img src="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['assetPath'][0], array( array('ns'=>"img",'file'=>"feat-img-01.png"),$_smarty_tpl ) );?>
" alt="">
                                        </div>
                                        <h4 class="font-size-18">
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature01Title'];?>

                                        </h4>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature01Description'];?>

                                        </p>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature01DescriptionTwo'];?>

                                        </p>
                                    </div>
                                    <!-- End: Feature 01 -->

                                    <!-- Start: Feature 02 -->
                                    <div class="col-md-12 feature-container clearfix">
                                        <div class="right-img">
                                            <img src="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['assetPath'][0], array( array('ns'=>"img",'file'=>"feat-img-02.png"),$_smarty_tpl ) );?>
" alt="">
                                        </div>
                                        <h4 class="font-size-18">
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature02Title'];?>

                                        </h4>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature02Description'];?>

                                        </p>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature02DescriptionTwo'];?>

                                        </p>
                                    </div>
                                    <!-- End: Feature 02 -->

                                    <!-- Start: Feature 03 -->
                                    <div class="col-md-12 feature-container clearfix">
                                        <div class="left-img">
                                            <img src="<?php echo $_smarty_tpl->tpl_vars['WEB_ROOT']->value;?>
/templates/orderforms/<?php echo $_smarty_tpl->tpl_vars['carttpl']->value;?>
/img/feat-img-03.jpg" alt="">
                                        </div>
                                        <h4 class="font-size-18">
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature03Title'];?>

                                        </h4>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature03Description'];?>

                                        </p>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['feature03DescriptionTwo'];?>

                                        </p>
                                    </div>
                                    <!-- End: Feature 03 -->

                                </div>
                            </div>
                            <!-- End: Features Content -->

                            <?php if ($_smarty_tpl->tpl_vars['productGroup']->value) {?>
                                <h3 class="text-center font-size-24"><?php echo $_smarty_tpl->tpl_vars['LANG']->value['cloudSlider']['selectProductLevel'];?>
</h3>

                                <!-- Start: Price Calculation Box -->
                                <div class="price-calc-container">
                                    <div class="price-calc-top">
                                        <div class="row clearfix">
                                            <div class="col-md-9" id="products-bottom">
                                                <input type="hidden" id="scroll-bottom" name="scroll-bottom" value="" />
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <span id="priceBottom" class="price-cont">--</span>
                                                <a href="#" class="order-btn" id="product-order-button-bottom">
                                                    <?php echo $_smarty_tpl->tpl_vars['LANG']->value['ordernowbutton'];?>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="price-calc-btm">

                                        <!-- Start: Progress Area Container -->
                                        <div id="productFeaturesBottom" class="row clearfix">
                                            <!-- Javascript will populate this area with product features. -->
                                        </div>
                                        <!-- End: Progress Area Container -->
                                    </div>
                                </div>
                                <!-- End: Price Calculation Box -->
                            <?php }?>
                        </div>

                    </div>
                </section>

            </div>

        </div>
    </div>

    <?php $_smarty_tpl->_subTemplateRender("file:orderforms/cloud_slider/recommendations-modal.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>
<!-- RangeSlider JS -->
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['BASE_PATH_JS']->value;?>
/ion.rangeSlider.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">

var sliderActivated = false;

var sliderProductNames = [
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products']->value, 'product', true);
$_smarty_tpl->tpl_vars['product']->iteration = 0;
$_smarty_tpl->tpl_vars['product']->index = -1;
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
$_smarty_tpl->tpl_vars['product']->iteration++;
$_smarty_tpl->tpl_vars['product']->index++;
$_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
$__foreach_product_1_saved = $_smarty_tpl->tpl_vars['product'];
?>
        "<?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
",
    <?php
$_smarty_tpl->tpl_vars['product'] = $__foreach_product_1_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
];

var allProducts = {
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products']->value, 'product', true, 'num');
$_smarty_tpl->tpl_vars['product']->iteration = 0;
$_smarty_tpl->tpl_vars['product']->index = -1;
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['num']->value => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
$_smarty_tpl->tpl_vars['product']->iteration++;
$_smarty_tpl->tpl_vars['product']->index++;
$_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
$__foreach_product_2_saved = $_smarty_tpl->tpl_vars['product'];
?>
        "<?php echo $_smarty_tpl->tpl_vars['num']->value;?>
": {
            "name": "<?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
",
            "desc": "<?php echo smarty_modifier_regex_replace(trim(nl2br($_smarty_tpl->tpl_vars['product']->value['featuresdesc'])),"/[\r\n]/",'');?>
",
            <?php if ((isset($_smarty_tpl->tpl_vars['product']->value['pid']))) {?>
                "pid": "<?php echo $_smarty_tpl->tpl_vars['product']->value['pid'];?>
",
                "displayPrice": "<?php echo $_smarty_tpl->tpl_vars['product']->value['pricing']['minprice']['price'];?>
",
                "displayCycle": "<?php echo $_smarty_tpl->tpl_vars['product']->value['pricing']['minprice']['cycle'];?>
",
            <?php } else { ?>
                "bid": "<?php echo $_smarty_tpl->tpl_vars['product']->value['bid'];?>
",
                "displayPrice": "<?php echo $_smarty_tpl->tpl_vars['product']->value['displayprice'];?>
",
                "displayCycle": "",
            <?php }?>
            "features": {
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['features'], 'feature', false, 'k');
$_smarty_tpl->tpl_vars['feature']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['feature']->value) {
$_smarty_tpl->tpl_vars['feature']->do_else = false;
?>
                    "<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
": "<?php echo $_smarty_tpl->tpl_vars['feature']->value;?>
",
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            },
            "featurePercentages": {
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['featurePercentages']->value, 'feature', false, 'featureKey');
$_smarty_tpl->tpl_vars['feature']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['featureKey']->value => $_smarty_tpl->tpl_vars['feature']->value) {
$_smarty_tpl->tpl_vars['feature']->do_else = false;
?>
                    <?php if ((isset($_smarty_tpl->tpl_vars['feature']->value[$_smarty_tpl->tpl_vars['num']->value]))) {?>
                        "<?php echo $_smarty_tpl->tpl_vars['featureKey']->value;?>
": "<?php echo $_smarty_tpl->tpl_vars['feature']->value[$_smarty_tpl->tpl_vars['num']->value];?>
",
                    <?php }?>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            },
            productUrl: '<?php echo $_smarty_tpl->tpl_vars['product']->value['productUrl'];?>
',
            hasRecommendations: '<?php echo $_smarty_tpl->tpl_vars['product']->value['hasRecommendations'];?>
'
        },
    <?php
$_smarty_tpl->tpl_vars['product'] = $__foreach_product_2_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
};

var definedProducts = {
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products']->value, 'product', true);
$_smarty_tpl->tpl_vars['product']->iteration = 0;
$_smarty_tpl->tpl_vars['product']->index = -1;
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
$_smarty_tpl->tpl_vars['product']->iteration++;
$_smarty_tpl->tpl_vars['product']->index++;
$_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
$__foreach_product_5_saved = $_smarty_tpl->tpl_vars['product'];
?>
        "<?php if ((isset($_smarty_tpl->tpl_vars['product']->value['pid']))) {
echo $_smarty_tpl->tpl_vars['product']->value['pid'];
} else { ?>b<?php echo $_smarty_tpl->tpl_vars['product']->value['bid'];
}?>": "<?php echo $_smarty_tpl->tpl_vars['product']->index;?>
"<?php if (!($_smarty_tpl->tpl_vars['product']->last)) {?>,
    <?php }?>
    <?php
$_smarty_tpl->tpl_vars['product'] = $__foreach_product_5_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
};

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products']->value, 'product', true);
$_smarty_tpl->tpl_vars['product']->iteration = 0;
$_smarty_tpl->tpl_vars['product']->index = -1;
$_smarty_tpl->tpl_vars['product']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->do_else = false;
$_smarty_tpl->tpl_vars['product']->iteration++;
$_smarty_tpl->tpl_vars['product']->index++;
$_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
$__foreach_product_6_saved = $_smarty_tpl->tpl_vars['product'];
?>
    <?php if ($_smarty_tpl->tpl_vars['product']->value['isFeatured']) {?>
        var firstFeatured = definedProducts["<?php if ((isset($_smarty_tpl->tpl_vars['product']->value['pid']))) {
echo $_smarty_tpl->tpl_vars['product']->value['pid'];
} else { ?>b<?php echo $_smarty_tpl->tpl_vars['product']->value['bid'];
}?>"];
        <?php break 1;?>
    <?php }
$_smarty_tpl->tpl_vars['product'] = $__foreach_product_6_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

var rangeSliderValues = {
    type: "single",
    grid: true,
    grid_snap: true,
    step: 1,
    onStart: updateFeaturesList,
    <?php if (count($_smarty_tpl->tpl_vars['products']->value) == 1) {?>
        disable: true,
    <?php }?>
    onChange: updateFeaturesList,
    values: sliderProductNames
};

<?php if ($_smarty_tpl->tpl_vars['pid']->value) {?>
    rangeSliderValues['from'] = definedProducts["<?php echo $_smarty_tpl->tpl_vars['pid']->value;?>
"];
<?php } else { ?>
    if (typeof firstFeatured != 'undefined') {
        rangeSliderValues['from'] = firstFeatured;
    }
<?php }?>

function updateFeaturesList(data)
{
    var featureName = "",
        featureMarkup = "",
        i = parseInt(data.from);
    if (isNaN(i)) {
        i = 0;
        jQuery(".irs-single").text(sliderProductNames[0]);
        jQuery(".irs-grid-text").text('');
    }
    var pid = allProducts[i].pid,
        bid = allProducts[i].bid,
        desc = allProducts[i].desc,
        features = allProducts[i].features,
        featurePercentages = allProducts[i].featurePercentages,
        displayCycle = '<br><small>' + allProducts[i].displayCycle + '</small>',
        displayPrice = allProducts[i].displayPrice + displayCycle,
        selectedId = data.input[0].id,
        featuresTargetArea = "",
        priceTargetArea = "",
        orderNowArea = "",
        buyLink = allProducts[i].productUrl,
        hasRecommendations = allProducts[i].hasRecommendations;

    if (selectedId == 'scroll-top') {
        if (sliderActivated) {
            jQuery("#scroll-bottom").data("ionRangeSlider").update({
               from:i
            });
        }
    } else {
        if (sliderActivated) {
            jQuery("#scroll-top").data("ionRangeSlider").update({
                from:i
            });
        }
    }

    // Clear the description.
    jQuery("#productFeaturesTop").empty();
    jQuery("#productFeaturesBottom").empty();

    // Update the displayed price.
    jQuery("#priceTop").html(displayPrice);
    jQuery("#priceBottom").html(displayPrice);

    // Update the href for the Order Now button.
    jQuery("#product-order-button").attr("href", buyLink);
    jQuery("#product-order-button-bottom").attr("href", buyLink);

    // Update data-has-recommendations attribute
    if (hasRecommendations) {
        jQuery('#product-order-button').attr('data-has-recommendations', hasRecommendations);
    }

    for (featureName in features) {
        featureMarkup = '<div class="col-md-3 container-with-progress-bar">' +
                            featureName +
                            '<span>' + features[featureName] + '</span>' +
                            '<div class="progress small-progress">' +
                                '<div class="progress-bar" role="progressbar" aria-valuenow="'+ featurePercentages[featureName] + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + featurePercentages[featureName] + '%;">' +
                                    '<span class="sr-only">' + featurePercentages[featureName] + '% Complete</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>';

        jQuery("#productFeaturesTop").append(featureMarkup);
        jQuery("#productFeaturesBottom").append(featureMarkup);
    }

    jQuery("#productDescription").html(desc);
}

jQuery("#scroll-top").ionRangeSlider(rangeSliderValues);
jQuery("#scroll-bottom").ionRangeSlider(rangeSliderValues);
<?php if (count($_smarty_tpl->tpl_vars['products']->value) == 1) {?>
    jQuery(".irs-single").text(sliderProductNames[0]);
    jQuery(".irs-grid-text").text('');
<?php }?>

sliderActivated = true;
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASE_PATH_JS']->value;?>
/whmcs/recommendations.min.js"><?php echo '</script'; ?>
>
<?php }
}
