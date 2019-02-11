<?php
require '../license/license.php';
require 'callAPI.php';
require 'admin_token.php';

$baseUrl = getMarketplaceBaseUrl();
$packageId = getPackageID();

$licence = new License();
if (!$licence->isValid()) {
    ?>
<!-- begin header -->
<link href="css/style.css" rel="stylesheet">
<!-- end header -->
<div class="subscription-container">
    <h2>Package Subscription page</h2>
    <div class="subscription-content">
        <p>You can try this package for 15 days. After that, it is $365/year.</p>
        <div class="btn-subscribe">
            <form action="" method="POST" id="subscription-form" enctype="application/x-www-form-urlencoded">
                <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="pk_test_tOoYZs4Lo85uKJGBLj2vDlXT" data-name="Package Name" data-description="Subscription for 1 year" data-amount="36500" data-label="Sign Me Up!">
                </script>
            </form>
            <form action="" method="POST" id="subscription-form" enctype="application/x-www-form-urlencoded">
                <a id="continue-trial" href="#">Continue trial</a>
            </form>
        </div>
          <div class="btn-subscribe">

        </div>
    </div>
</div>
<!-- begin footer -->
<script type="text/javascript" src="scripts/subscription.js"></script>
<!-- end footer -->
<?php
} else {
    $location = $baseUrl . '/admin/plugins/' . $packageId . '/index.php';
    header('Location: ' . $location);
}
?>