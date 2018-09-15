<style>
.section{
    margin-left: -20px;
    margin-right: -20px;
    font-family: "Raleway",san-serif;
}
.section h1{
    text-align: center;
    text-transform: uppercase;
    color: #808a97;
    font-size: 35px;
    font-weight: 700;
    line-height: normal;
    display: inline-block;
    width: 100%;
    margin: 50px 0 0;
}
.section ul{
    list-style-type: disc;
    padding-left: 15px;
}
.section:nth-child(even){
    background-color: #fff;
}
.section:nth-child(odd){
    background-color: #f1f1f1;
}
.section .section-title img{
    display: table-cell;
    vertical-align: middle;
    width: auto;
    margin-right: 15px;
}
.section h2,
.section h3 {
    display: inline-block;
    vertical-align: middle;
    padding: 0;
    font-size: 24px;
    font-weight: 700;
    color: #808a97;
    text-transform: uppercase;
}

.section .section-title h2{
    display: table-cell;
    vertical-align: middle;
    line-height: 25px;
}

.section-title{
    display: table;
}

.section h3 {
    font-size: 14px;
    line-height: 28px;
    margin-bottom: 0;
    display: block;
}

.section p{
    font-size: 13px;
    margin: 25px 0;
}
.section ul li{
    margin-bottom: 4px;
}
.landing-container{
    max-width: 750px;
    margin-left: auto;
    margin-right: auto;
    padding: 50px 0 30px;
}
.landing-container:after{
    display: block;
    clear: both;
    content: '';
}
.landing-container .col-1,
.landing-container .col-2{
    float: left;
    box-sizing: border-box;
    padding: 0 15px;
}
.landing-container .col-1 img{
    width: 100%;
}
.landing-container .col-1{
    width: 55%;
}
.landing-container .col-2{
    width: 45%;
}
.premium-cta{
    background-color: #808a97;
    color: #fff;
    border-radius: 6px;
    padding: 20px 15px;
}
.premium-cta:after{
    content: '';
    display: block;
    clear: both;
}
.premium-cta p{
    margin: 7px 0;
    font-size: 14px;
    font-weight: 500;
    display: inline-block;
    width: 60%;
}
.premium-cta a.button{
    border-radius: 6px;
    height: 60px;
    float: right;
    background: url(<?php echo YWAF_ASSETS_URL?>/images/upgrade.png) #ff643f no-repeat 13px 13px;
    border-color: #ff643f;
    box-shadow: none;
    outline: none;
    color: #fff;
    position: relative;
    padding: 9px 50px 9px 70px;
}
.premium-cta a.button:hover,
.premium-cta a.button:active,
.premium-cta a.button:focus{
    color: #fff;
    background: url(<?php echo YWAF_ASSETS_URL?>/images/upgrade.png) #971d00 no-repeat 13px 13px;
    border-color: #971d00;
    box-shadow: none;
    outline: none;
}
.premium-cta a.button:focus{
    top: 1px;
}
.premium-cta a.button span{
    line-height: 13px;
}
.premium-cta a.button .highlight{
    display: block;
    font-size: 20px;
    font-weight: 700;
    line-height: 20px;
}
.premium-cta .highlight{
    text-transform: uppercase;
    background: none;
    font-weight: 800;
    color: #fff;
}

<!--.section.one{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/01-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.two{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/02-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.three{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/03-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.four{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/04-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.five{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/05-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.six{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/06-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.seven{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/07-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.eight{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/08-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.nine{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/09-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.ten{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/10-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->
<!--.section.eleven{-->
<!--    background: url(--><?php //echo YWAF_ASSETS_URL ?><!--/images/11-bg.png) no-repeat #fff; background-position: 85% 75%-->
<!--}-->


@media (max-width: 768px) {
    .section{margin: 0}
    .premium-cta p{
        width: 100%;
    }
    .premium-cta{
        text-align: center;
    }
    .premium-cta a.button{
        float: none;
    }
}

@media (max-width: 480px){
    .wrap{
        margin-right: 0;
    }
    .section{
        margin: 0;
    }
    .landing-container .col-1,
    .landing-container .col-2{
        width: 100%;
        padding: 0 15px;
    }
    .section-odd .col-1 {
        float: left;
        margin-right: -100%;
    }
    .section-odd .col-2 {
        float: right;
        margin-top: 65%;
    }
}

@media (max-width: 320px){
    .premium-cta a.button{
        padding: 9px 20px 9px 70px;
    }

    .section .section-title img{
        display: none;
    }
}
</style>
<div class="landing">
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Anti-Fraud%2$s to benefit from all features!','yith-woocommerce-anti-fraud'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-woocommerce-anti-fraud');?></span>
                    <span><?php _e('to the premium version','yith-woocommerce-anti-fraud');?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="one section section-even clear">
        <h1><?php _e('Premium Features','yith-woocommerce-anti-fraud');?></h1>
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/01.png" alt="Feature 01" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e('Risk limits','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Configure the risk limits to create a percentage value for which orders are considered %1$strusted%2$s, likely fraudulent or highly dangerous.', 'yith-woocommerce-anti-fraud'), '<b>', '</b>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="two section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e('Weight','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Each rule applied has a specific weight on the generation of the fraud %1$srisk percentage%2$s of an order. Not all rules could have the same risk coefficient, and this is why we have decided to let you configure the rules as you wish.', 'yith-woocommerce-anti-fraud'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/02.png" alt="feature 02" />
            </div>
        </div>
    </div>
    <div class="three section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/03.png" alt="Feature 03" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e( 'Suspicious email domains','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Activate the email verification and the system will warn you if some are related to %1$sambiguous domains%2$s. For example, "yopmail.net", "walkmail.net" or "webemail.me" are only some of the many pre-set domains of the plugin. You will be free to add new or remove them, in order to have an up-to-date list for a cutting edge site.', 'yith-woocommerce-anti-fraud'), '<b>', '</b>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="four section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e('Order total','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Activate the check on the total amount of the order and every time this will be extremely higher the average purchases of the shop, the plugin will consider it automatically as a %1$spotential danger%2$s. %3$sBe always aware of the possible anomalies!', 'yith-woocommerce-anti-fraud'), '<b>', '</b>','<br>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/04.png" alt="Feature 04" />
            </div>
        </div>
    </div>
    <div class="five section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/05.png" alt="Feature 05" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e('Too many sudden orders?','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('It is hard to find multiple orders made by the same IP in a few hours. If you think this is an odd behavior for your e-commerce site too, set the %1$smaximum number of orders allowed in a time span%2$s. If exceeded, the plugin will warn you immediately.','yith-woocommerce-anti-fraud'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="six section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e('Proxy','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('If the IP address of the people who try to purchase can be identified as behind a proxy, %1$sYITH WooCommerce Anti-Fraud%2$s will signal the order as a possible fraud. Few clicks and your shop will be safer and safer to fraudulent purchases.','yith-woocommerce-anti-fraud'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/06.png" alt="Feature 06" />
            </div>
        </div>
    </div>
    <div class="seven section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/07.png" alt="Feature 07" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e('Check on billing information','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('The premium version of the plugin lets you check the billing information of those users that purchased something with the %1$ssame IP%2$s in the past. In this case, the risk level of the order will increase when different information will be used instead of those already stored in the shop.','yith-woocommerce-anti-fraud'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="eight section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e( 'Customers origin','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Make a verification on all orders made by users from those you can select as "dangerous" countries. Depending on the weight you give to the rule, the order will be even %1$sblocked automatically%2$s when the order will be identified as a high level fraud risk.','yith-woocommerce-anti-fraud'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/08.png" alt="Feature 08" />
            </div>
        </div>
    </div>
    <div class="nine section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/09.png" alt="Feature 09" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e('Blacklist','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('A complete list of all emails linked to orders that are identified as %1$shighly dangerous%2$s. The email addresses are automatically added by the plugin, but you are always free to remove them when you want.','yith-woocommerce-anti-fraud'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="ten section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e( 'Cancel orders','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('All %1$shigh-risk orders%2$s are automatically cancelled by the plugin: a vital safety rule to protect your site from the most dangerous purchases. Also, you could always change their states in case those orders should be revealed as correct.','yith-woocommerce-anti-fraud'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/10.png" alt="Feature 10" />
            </div>
        </div>
    </div>
    <div class="eleven section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YWAF_ASSETS_URL ?>/images/11.png" alt="Feature 11" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <h2><?php _e('PayPal','yith-woocommerce-anti-fraud');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('A particular attention is given to those purchases made with a PayPal account. Once the order will be done, an email will be sent to the %1$sPayPal address%2$s of the user: if the address won\'t be verified, the order will be cancelled automatically.','yith-woocommerce-anti-fraud'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Anti-Fraud%2$s to benefit from all features!','yith-woocommerce-anti-fraud'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-woocommerce-anti-fraud');?></span>
                    <span><?php _e('to the premium version','yith-woocommerce-anti-fraud');?></span>
                </a>
            </div>
        </div>
    </div>
</div>