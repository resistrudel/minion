    <!-- GOOGLE -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', <?php echo '\''.$brands[$brand]['UAcode'].'\'';?>, 'auto');
        ga('send', 'pageview');

    </script>

    <!-- OMNITURE -->
    <script type="text/javascript">
        var s_account="globalrpromositesexternal,<?php echo $brands[$brand]['tracking']; ?>",
            v_page="MKT/EXTR/<?php echo $client['key'], '/', $campaign; ?>/";
    </script>
    <script type="text/javascript" src="/_shared/scripts/s_code.js"></script>
    <script type="text/javascript"><!--
        /* You may give each page an identifying name, server, and channel on the next lines. */
                   s.pageName="MKT/EXTR/<?php echo $client['key'], '/', $campaign, '/',  $page; ?>"
                 s.channel="MKT"
                 s.pageType=""
                 s.prop1="MKT/EXTR"
                 s.prop2="MKT/EXTR/<?php echo $client['key']; ?>"
                 s.prop3="MKT/EXTR/<?php echo $client['key'], '/', $campaign; ?>"
                 s.prop4=""
                 s.prop5=""
                 s.prop6="<?php echo $page; ?>"
                 s.prop7=""
                 s.prop8=""
                 s.prop9=""
                 s.prop10=""
                 s.prop11=""
                 s.prop12=""
                 s.prop13=""
                 s.prop14=""
                 s.prop15=""
                 s.prop16=""
                 s.prop17=""
                 s.prop18="not_signed_in"
                 s.prop19=""
                 s.prop20=""
                 s.prop21=""
                 s.prop22=""
                 s.prop23=""
                 s.prop24="<?php echo $client['key']; ?>"
                 s.prop25="false"
                 s.prop26="<?php echo $client['key']; ?>"
                 s.prop27="prizes"
                 s.products=""
                 /* Conversion Variables */
                 s.campaign=""
                 s.events=""
                 s.eVar1=""
                 s.eVar2=""
                 s.eVar3=""
                 s.eVar4=""
                 s.eVar5=""
                 s.eVar6=""
                 s.eVar7=""
                 s.eVar8=""
                 s.eVar9=""
                 s.eVar10=""
                 s.eVar11=""
                 s.eVar12=""
                 s.eVar13=""
                 s.eVar14=""
                 s.eVar15=""
                 s.eVar16=""
                 s.eVar17=""
                 s.eVar18=""
                 s.eVar19=""
                 s.eVar20=""
                 s.eVar21=""
                 s.eVar22=""
                 s.eVar23=""
                 s.eVar24=""
                 s.eVar25=""
                 /* Hierarchy Variables */
                 s.hier1="<?php echo $client['key'], ',', $page; ?>"
                 s.hier2="MKT/EXTR/<?php echo $client['key'], '/', $campaign; ?>"
                /* Stream Tracking Variables */
                 s.Media.autotrack=true;

             /************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
             var s_code=s.t();if(s_code)document.write(s_code)
        //--></script>

    <script type="text/javascript"><!--if(navigator.appVersion.indexOf('MSIE')>=0)document.write(unescape('%3C')+'\!-'+'-') //--></script>
    <noscript><img src="http://c.musicradio.com/b/ss/GlobalRDev/1/H.20.2--NS/0?[AQB]&amp;cdp=3&amp;[AQE]" id="tracking" height="1" width="1" alt="" /></noscript><!--/DO NOT REMOVE/-->
    <!-- End SiteCatalyst code version: H.20.2. -->