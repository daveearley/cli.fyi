<?php
if ($googleAnalyticsId = getenv('GOOGLE_ANALYTICS_ID')) { ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-29387038-6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', <?=$googleAnalyticsId?>);
    </script>
<?php } ?>