## Integrating Stripe in your Plug-In

This will run you through the steps to integrate Stripe into your plug-in and get paid for your plug-in’s usage.

### Subscription payment

1. Create a Stripe Dashboard Account and find your API keys

Navigate to dashboard.stripe.com and register for an account. The dashboard looks like this:

![Stripe Dashboard](https://bootstrap.arcadier.com/github/A.PNG)

Since we will be dealing with Stripe's APIs, you need to know your publishable and secret keys; they are found under the **Developers > API keys** section.

![API keys](https://bootstrap.arcadier.com/github/B.PNG)


2. Create a Product

Another thing you will need before integrating Stripe to your Plug-in, is a "Product". Go to **Billing** - it will drop down -  and select **Products** and Click on **"Add a new Product"**.

![New Product](https://bootstrap.arcadier.com/github/C.PNG)

This "Product" is basically your Plug-In, so go ahead and give it its details.

![New Product](https://bootstrap.arcadier.com/github/D.PNG)

Decide its pricing plan:

![New Product](https://bootstrap.arcadier.com/github/E.PNG)

![New Product](https://bootstrap.arcadier.com/github/F.PNG)

Now, you have all the information needed at hand; the *product ID* and the **plan ID**. The **plan ID** can be found by clicking on the Pricing Plan, "Hello World Money" in this example.

 
The source code to charge recurring payments from your customers is hosted on Arcadier’s Github (https://github.com/Arcadier/Plug-In-Demos/tree/master/stripe-subscription). The flowchart provides a brief overview of how it works.







