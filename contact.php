<?php
require 'config.php';
require 'header.php';
?>
<!------ Include the above in your HEAD tag ---------->

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        /* Conatct start */
        .header-title {
            text-align: center;
        }

        #tip {
            display: none;
        }

        .fadeIn {
            animation-duration: 3s;
        }

        .form-control {
            border-radius: 0px;
            border: 1px solid #EDEDED;
        }

        .form-control:focus {
            border: 1px solid #00bfff;
        }

        .textarea-contact {
            resize: none;
        }

        .btn-send {
            border-radius: 0px;
            border: 1px solid #00bfff;
            background: #00bfff;
            color: #fff;
        }

        .btn-send:hover {
            border: 1px solid #00bfff;
            background: #fff;
            color: #00bfff;
            transition: background 0.5s;
        }

        .second-portion {
            margin-top: 30px;
        }

        @import "//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css";
        @import "http://fonts.googleapis.com/css?family=Roboto:400,500";

        .box>.icon {
            text-align: center;
            position: relative;
        }

        .box>.icon>.image {
            position: relative;
            z-index: 2;
            margin: auto;
            width: 88px;
            height: 88px;
            border: 8px solid white;
            line-height: 88px;
            border-radius: 50%;
            background: #1A75A1;
            vertical-align: middle;
        }

        .box>.icon:hover>.image {
            background: #333;
        }

        .box>.icon>.image>i {
            font-size: 36px !important;
            color: #fff !important;
        }

        .box>.icon:hover>.image>i {
            color: white !important;
        }

        .box>.icon>.info {
            margin-top: -24px;
            background: rgba(0, 0, 0, 0.04);
            border: 1px solid #e0e0e0;
            padding: 15px 0 10px 0;
            min-height: 163px;
        }

        .box>.icon:hover>.info {
            background: rgba(0, 0, 0, 0.04);
            border-color: #e0e0e0;
            color: white;
        }

        .box>.icon>.info>h3.title {
            font-family: "Robot", sans-serif !important;
            font-size: 16px;
            color: #222;
            font-weight: 700;
        }

        .box>.icon>.info>p {
            font-family: "Robot", sans-serif !important;
            font-size: 13px;
            color: #666;
            line-height: 1.5em;
            margin: 20px;
        }

        .box>.icon:hover>.info>h3.title,
        .box>.icon:hover>.info>p,
        .box>.icon:hover>.info>.more>a {
            color: #222;
        }

        .box>.icon>.info>.more a {
            font-family: "Robot", sans-serif !important;
            font-size: 12px;
            color: #222;
            line-height: 12px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .box>.icon:hover>.info>.more>a {
            color: #fff;
            padding: 6px 8px;
            background-color: #63B76C;
        }

        .box .space {
            height: 30px;
        }

        @media only screen and (max-width: 768px) {
            .contact-form {
                margin-top: 25px;
            }

            .btn-send {
                width: 100%;
                padding: 10px;
            }

            .second-portion {
                margin-top: 25px;
            }
        }

        /* Conatct end */
    </style>
</head>
<div class="container animated fadeIn">
<h1 class="text-center">ติดต่อเรา</h1>
    <div class="row">
        <hr>
        <div class="col-sm-12" id="parent">
            <div class="col-sm-12">
                <iframe width="100%" height="320px;" frameborder="0" style="border:0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.0745083861993!2d100.49542971477338!3d7.000507994943051!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304d299a1a278305%3A0x6d694ff4d24d4286!2z4Lia4Lij4Li04Lip4Lix4LiXIOC5gOC4meC5h-C4geC4i-C5jOC4ruC4reC4myDguIjguLPguIHguLHguJQgKE5FWFQtSE9QIFNvZnR3YXJlIGFuZCBOZXR3b3JrIFNvbHV0aW9uKQ!5e0!3m2!1sen!2sth!4v1588054203210!5m2!1sen!2sth" allowfullscreen></iframe>
            </div>

        </div>
    </div>
    <hr>
    <div class="container second-portion">
        <div class="row">
            <!-- Boxes de Acoes -->
            <div class="col-xs-12 col-sm-6 col-lg-4">
                <div class="box">
                    <div class="icon">
                        <div class="image"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                        <div class="info">
                            <h3 style="margin-top:20px;" class="title">MAIL & WEBSITE</h3>
                            <p>
                                <i class="fa fa-envelope" aria-hidden="true"></i> &nbsp <a href="mailto:hello@nexthop.co.th">hello@nexthop.co.th</a>
                                <br>
                                <br>
                                <i class="fa fa-globe" aria-hidden="true"></i> &nbsp <a target="_blank" href="http://www.nexthop.co.th">www.nexthop.co.th</a>
                            </p>

                        </div>
                    </div>
                    <div class="space"></div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-lg-4">
                <div class="box">
                    <div class="icon">
                        <div class="image"><i class="fa fa-mobile" aria-hidden="true"></i></div>
                        <div class="info">
                            <h3 style="margin-top:20px;" class="title">CONTACT</h3>
                            <p>
                                <i class="fa fa-mobile" aria-hidden="true"></i> &nbsp <a href="tel:0802107343">(+66)-02107343</a>
                                <br>
                                <br>
                                <i class="fa fa-mobile" aria-hidden="true"></i> &nbsp <a href="tel:0887567100">088-756-7100</a>
                            </p>
                        </div>
                    </div>
                    <div class="space"></div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-lg-4">
                <div class="box">
                    <div class="icon">
                        <div class="image"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                        <div class="info">
                            <h3 style="margin-top:20px;" class="title">ADDRESS</h3>
                            <p>
                                <i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp 320 Thungree Rd, Kho Hong, Hat Yai District, Songkhla 90110
                            </p>
                        </div>
                    </div>
                    <div class="space"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
require 'footer.php';
?>