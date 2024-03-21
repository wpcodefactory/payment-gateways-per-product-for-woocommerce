=== Payment Methods by Product & Country for WooCommerce ===
Contributors: wpcodefactory, omardabbas, karzin, anbinder, algoritmika, kousikmukherjeeli
Tags: woocommerce, payment gateway, conditional-payments, payment by product, payment by country
Requires at least: 4.4
Tested up to: 6.4
Stable tag: 1.7.11
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Use products and countries conditional rules to show/hide gateways, increase profit margins & optimize operations for your products by restricting payment methods in WooCommerce checkout page

== Description ==

> “Works great, WPML compatible!: Great plugin! Glad it supports many languages (I use WPML)” – ⭐⭐⭐⭐⭐  [alexio101](https://wordpress.org/support/topic/works-great-wpml-compatible/)

Every payment gateway has its own advantages/disadvantages, they are not equal when it comes to fees, adaptability from customers, regional popularity, and even in security as some gateways are known for larger fraud cases than others.

Using conditional/custom payment methods for your store to restrict what gateways appear for specific products comes handy here, where you will be able to show/hide payment gateways based on what's in the cart.

For most stores, PayPal is considered an expensive payment gateway, and when you're selling expensive products (hundreds or probably thousands), you want to prevent users from checking out using PayPal and instead, use wire transfers or even local payment gateways that offer competitive rates, where you can keep your profit margins higher.

In a nutshell, this is what you can expect to get with this plugin:

1. Control what payment gateways are available/unavailable based on product category.
2. Control what payment gateways are available/unavailable based on product tag.
3. Control what payment gateways are available/unavailable by product level (Pro).
4. Control what payment gateways are available/unavailable by Country (Pro).
5. Show a fallback gateway in the case of gateways allow & disallow conflict (Pro).

Let's get into more details and see what features the plugin offers.

#### Useful Links ####
* **[Plugin Main Page](https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "Plugin Main Page")**
* **[Plugin Demo](https://paymentbyproduct.instawp.xyz/ "Plugin Demo")**
* **[Plugin Support Forum](https://wpfactory.com/support/item/payment-gateways-per-product-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "Plugin Support Forum")**
* **[Documentation & How to](https://wpfactory.com/docs/payment-gateways-per-product-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "Documentation & How to")**

## 🤝 Recommended By##
* [PPWP Pro: How to Restrict WooCommerce Payment Methods Based on Product Types](https://passwordprotectwp.com/restrict-woocommerce-payment-methods-product-types/ "PPWP Pro: How to Restrict WooCommerce Payment Methods Based on Product Types")
* [SKT Themes: Top Payment Gateways Plugins You MUST Have](https://www.sktthemes.org/wordpress-plugins/wordpress-payment-gateway-plugins/ "SKT Themes: Top Payment Gateways Plugins You MUST Have")
* [Web Programacion: How to Specify Payment Gateways to Each Product in WooCommerce](https://webprogramacion.com/especifica-con-que-pasarela-pagar-cada-producto-en-woocommerce/ "Web Programacion: How to Specify with which gateway to pay each product in WooCommerce")

## 🚀 Main Features: FREE Version ##

== The plugin works in 2 modes: ==

It lets you select what payment gateways to show if a product category or tag is added (meaning hide all other gateways in this case).
Second, lets you select what gateways to hide when a selected product category or product is in the cart (i.e. all other gateways will appear).

=== Examples: ===

Category A is sold using all payment gateways, no restrictions.

Category B is sold using all gateways except PayPal.

Category C is sold only using wire transfer (very high price).

You can configure the plugin to reflect the above 3 cases like the following:

Category A: untouched, won't be included/excluded from the plugin settings.

Category B: Under PayPal gateway, we insert category B on the "Excluded" section.

Category C: Add it to the "Excluded" section of all other gateways.

Note: Adding category C to the "Included" section of wire transfer will hide this gateway from all other categories, so you have to be either "allow all except" or "hide all except"

### 🚀 Restrict Payment Gateway Visibility by Product Category ###

This feature allows you to control which payment gateways are available for specific product categories. 
For instance, if you have products in Category C that should only be purchased via wire transfer due to their high price, you can set this category to be exclusively associated with the wire transfer payment gateway. 
Similarly, for Category B, you can exclude PayPal as a payment option, while Category A remains unrestricted, compatible with all payment gateways.

### 🚀 Restrict Payment Gateway Visibility by Product Tag ###

Similar to the category-based control, this mode lets you specify payment gateways based on product tags. 
This means you can have even finer control by tagging specific products and associating them with certain payment methods. For example, if a product is tagged with a particular label indicating a special payment condition, the plugin will automatically adjust the available payment gateways for that product in the cart.

### 🚀 Intuitive & easy to use interface ###

By default, the plugin doesn't change anything on installation & activation, once you decide what gateways to show/hide for product categories or tags, go to WooCommerce >> Settings >> Payment Gateways per Products" and under desired tab (category or tag), start including/excluding categories on respective gateways you've set.

### 🚀 Global Support For All Gateways ###

The plugin supports any gateway (standard or customized), all gateways that are installed & enabled on WooCommerce >> Settings >> Payments will be supported, and appear on plugin settings, where you will be able to conditionally control what product categories or tags appear on each gateway.

### 🚀 Plugin Use Cases ###

1. Expensive products: This might be the most use case for this plugin, you want to restrict customers buying expensive items to pay using wire transfer only.

2. Cheap products: Imagine you have to deal with a wire transfer or cash on delivery for an $7 item, does that make sense to your business operations? The plugin can restrict gateways based on products of your choice.

3. Subscription products: when you sell products that need monthly/yearly renewal, you can't/shouldn't allow checking out on gateways that don't support automatic renewals (like CoD), instead, here you can restrict users to checkout using PayPal for example.

4. Products with very low margins: Some products (even sold at good price points) might have low margins (couple of dollars) because of the competition, in such conditions, you might want to limit the allowed payment methods to those who offer very low fees.

> “I wanted to make the payment method for cheap products different than for the more expensive products.
This plugin is doing the job very good!” – ⭐⭐⭐⭐⭐ [phdhont](https://wordpress.org/support/topic/works-very-good-82/)

> “Works great for tags: Great free resource, I also appreciate the test area, thanks guys, you deserve 5 stars.” – ⭐⭐⭐⭐⭐ [SeaLuke](https://wordpress.org/support/topic/works-great-for-tags/)

## 🏆 Pro Version ##

Our **[Plugin Pro version](https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "Plugin Pro version")** features further expands the capabilities of our plugin so you can have more control on product & gateways restrictions, like:

### 🏆 Payment Method Control at Product and Variation Level ###

This functionality extends your control beyond categories and tags, allowing you to specify payment gateways for individual products and even their variations. 

This granular level of customization is perfect for unique items or specific variations that require special payment handling. 

For example, you could set a high-end product variation to only be purchasable through credit card transactions, while a standard version of the product might be available for purchase through multiple payment methods

### 🏆 Fallback Payment Method Selection ###

In scenarios where your cart contains mixed products from different rules, potentially leading to a conflict where no payment method is available, this feature comes into play. 

It allows you to designate a fallback payment gateway for such cases. This ensures that there's always an available payment option for customers, even when their cart contains a complex mix of products with different payment gateway rules.

### 🏆 Payment Gateway Restriction by Country ###

This feature allows you to tailor the availability of payment gateways based on the customer's billing country. 

This is particularly useful for businesses that operate internationally and need to comply with various regional financial regulations or want to offer localized payment options. 

For instance, you might restrict certain payment methods to customers in the EU while offering different options to those in the US. 

Additionally, it can be used to limit expensive international transaction fees for certain regions by offering local payment solutions, or to comply with regional restrictions on certain payment services.

## 💯 Why WPFactory? ##

* **Experience You Can Trust:** Over a decade in the business
* **Wide Plugin Selection:** Offering 65+ unique and powerful plugins
* **Highly-Rated Support:** Backed by hundreds of 5-star reviews
* **Expert Team:** Dedicated developers and technical support at your service


## What's Next? Check More Plugins by WPFactory##


If you're enjoying our plugin, we'd love for you to explore our other offerings. WPFactory has a diverse range of plugins tailored to enhance your experience. 

Dive in and discover more tools to empower your WooCommerce Store!


* [**Min Max Step Quantity**](https://wpfactory.com/item/product-quantity-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "**Min Max Step Quantity**"): Define a min max, step and default quantity for products, show a dropdown, quantities on archive/categories pages, use decimal quantities, and much more on WooCommerce stores (**[Try our Free version](https://wordpress.org/plugins/product-quantity-for-woocommerce/ "Try our Free version")**)

* [**Cost of Goods for WooCommerce**](https://wpfactory.com/item/cost-of-goods-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "**Cost of Goods WooCommerce**"): Make informed decisions to maximize profits, correctly calculate Cost of Goods Sold (COGS) for your WooCommerce store and enhance your financial management capabilities (**[Try our Free version](https://wordpress.org/plugins/cost-of-goods-for-woocommerce/ "Try our Free version")**)

* [**Maximum Products per User**](https://wpfactory.com/item/maximum-products-per-user-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "**Maximum Products per User**"): Set personalized purchase limits for your customers, define maximum product quantities, catered to specific user roles & selected date range (**[Try our Free version](https://wordpress.org/plugins/maximum-products-per-user-for-woocommerce/ "Try our Free version")**)

* [**Order Minimum/Maximum Amount**](https://wpfactory.com/item/order-minimum-maximum-amount-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "**Order Minimum/Maximum Amount**"): Set tailored minimum and maximum order thresholds, by sum, quantity, weight, or volume, customize limits by user role, specific user, product category, shipping method, payment gateway, or even by currency (**[Try our Free version](https://wordpress.org/plugins/order-minimum-amount-for-woocommerce/ "Try our Free version")**)


* [**EU/UK VAT Manager for WooCommerce**](https://wpfactory.com/item/eu-vat-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "**EU/UK VAT Manager for WooCommerce**"): Streamline your WooCommerce store’s EU/UK VAT compliance effortlessly, automate VAT settings, validation (VIES), and how to apply taxes, ensuring a seamless and compliant customer experience (**[Try our Free version](https://wordpress.org/plugins/eu-vat-for-woocommerce/ "Try our Free version")**)

* [**Email Verification for WooCommerce**](https://wpfactory.com/item/email-verification-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "**Email Verification for WooCommerce**"): Enhance WooCommerce security and credibility with Email Verification best plugin. Ensure genuine customer interactions, eliminate spam, and elevate email marketing efficiency (**[Try our Free version](https://wordpress.org/plugins/maximum-products-per-user-for-woocommerce/ "Try our Free version")**)

* [**Free Shipping Over Amount for WooCommerce**](https://wpfactory.com/item/amount-left-free-shipping-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "**Free Shipping Over Amount for WooCommerce**"): WooCommerce Advanced Free Shipping plugin, use our plugin to quality customers for free shipping when they spend specific amount, by showing a bar on remaining amounts they need to spend to qualify for free shipping (**[Try our Free version](https://wordpress.org/plugins/amount-left-free-shipping-woocommerce/ "Try our Free version")**)

* [**Dynamic Pricing & Bulk Quantity Discounts**](https://wpfactory.com/item/product-price-by-quantity-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme "**Dynamic Pricing & Bulk Quantity Discounts**"): Create and manage advanced dynamic pricing and bulk discount rules for WooCommerce, encouraging bulk purchases and driving your sales to new heights (**[Try our Free version](https://wordpress.org/plugins/wholesale-pricing-woocommerce/ "Try our Free version")**)

###❤️ USER TESTIMONIALS: SEE WHAT OTHERS ARE SAYING! ###

> “If you need granularity on payment gateways related to products, categories or tags - this is your plugin.
Great plugin and great support!” – ⭐⭐⭐⭐⭐ [Asger Laursen](https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme)

> “Thank you for the quick and professional support! Good job!” – ⭐⭐⭐⭐⭐ [Iryna](https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme)

> “Excellent work. I bought the plugin because I needed to regulate my payment method on a particular product with variations and also on a particular category. It works great.” – ⭐⭐⭐⭐⭐ [Peter Domaracky](https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/?utm_source=wporg&utm_medium=organic&utm_campaign=readme)

> “Great plugin – It works flawlessly: Great plugin! It works flawlessly. I use this plugin in combination with All in One Product Quantity for WooCommerce – by the same author. I am sincerely delighted. I do not understand the ratings and support questions in which some users say that the plugin does not work?! The plugin works exactly as described. The settings are very simple.” – ⭐⭐⭐⭐⭐ [vipteam](https://wordpress.org/support/topic/great-plugin-it-works-flawlessly/)


== Screenshots ==

1. Main Page
2. Specify settings per category
3. Specify settings per tag

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Payment Gateways per Products".

== Changelog ==

= 1.7.11 - 21/03/2024 =
* Add - Add filter > On "wp_loaded" action.

= 1.7.10 - 20/03/2024 =
* Update readme.txt

= 1.7.9 - 12/02/2024 =
* WC tested up to: 8.5.
* Tested up to: 6.4.
* New - multiple payment by country restrictions.

= 1.7.8 - 09/11/2023 =
* Declare HPOS compatibility.
* WC tested up to: 8.1.

= 1.7.7 - 21/09/2023 =
* Update logo.

= 1.7.6 - 21/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.

= 1.7.5 - 18/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 1.7.4 - 31/03/2023 =
* Fix plugin name.
* Move to WPFactory.

= 1.7.3.1 - 15/02/2023 =
* Bug fix appeared in version 1.7.3 causing PHP fatal error
* Verified compatibility with WooCommerce 7.4

= 1.7.3 - 13/02/2023 =
* New feature (Pro): enable/disable payment method by country
* Verified compatibility with WooCommerce 7.3

= 1.7.2 - 04/11/2022 =
* Verified compatibily with WordPress 6.1 & WooCommerce 7.0

= 1.7.1 - 12/06/2022 =
* Verified compatibily with WordPress 6.0 & WooCommerce 6.5

= 1.7 - 18/04/2022 =
* Fixed an uncaught error related to a JS file (select2)
* Fixed a bug in client area where gateways were hidden without products in cart
* Verified compatibily with WooCommerce 6.4

= 1.6.4 - 28/01/2022 =
* Allowed mixing includes/excludes while giving priority to product-defined settings over category/attribute
* Verified compatibily with WooCommerce 6.2 

= 1.6.3 - 28/01/2022 =
* Verified compatibily with WordPress 5.9 & WooCommerce 6.1

= 1.6.2 - 10/11/2021 =
* Fixed a bug in WPML compatibility when switching between languages settings were lost
* Verified compatibility with WooCommerce 5.9

= 1.6.1 - 29/10/2021 =
* Fixed a bug in showing category IDs instead of names for some users after 1.4.5

= 1.6 - 26/10/2021 =
* Fixed a bug in 1.4.5 preventing Pro users from using Pro features
* Verified compatibility with WooCommerce 5.8

= 1.4.5 - 19/10/2021 =
* Fixed a bug showing category IDs instead of category names
* Allowed choosing payment method from product edit page directly

= 1.4.4 - 16/10/2021 =
* Fixed multiple issues (error 500) for stores with thousands of products
* Verified compatibility with WooCommerce 5.7

= 1.4.3 - 30/08/2021 =
* Checked & verified compatibility with WooCommerce 5.6

= 1.4.2 - 16/08/2021 =
* Fixed a bug not showing specific custom gateways
* Added an integration to manually added orders emails to show/hide gateways as in store

= 1.4.1 - 25/07/2021 =
* Tested compatibilty with WC 5.5 & WP 5.8

= 1.4 - 16/05/2021 =
* New feature: Fallback gateway to show a selected gateway if mixed products (with different gateways) are in cart.
* Verified compatibility with WooCommerce 5.3

= 1.3.4 - 20/04/2021 =
* Tested compatibilty with WC 5.1 & WP 5.7

= 1.3.3 - 28/02/2021 =
* Tested compatibilty with WC 5.0

= 1.3.2 - 27/01/2021 =
* Tested compatibility with WP 5.6 & WC 4.9

= 1.3.1 - 21/11/2020 =
* Tested compatibility with WC 4.7

= 1.3 - 15/08/2020 =
* Tested compatibility with WP 5.5
* Tested compatibility with WC 4.3

= 1.2.1 - 20/11/2019 =
* Dev - Code refactoring.
* WC tested up to: 3.8.
* Tested up to: 5.3.
* Plugin author changed.

= 1.2.0 - 12/07/2019 =
* Dev - Advanced Options - Add filter - Default value set to `On "init" action`.
* Dev - Per Products - Adding product ID to the list of products in settings.
* Dev - Code refactoring.

= 1.1.1 - 24/05/2019 =
* Dev - Admin Settings - "Your settings have been reset" notice added.
* Tested up to: 5.2.
* WC tested up to: 3.6.

= 1.1.0 - 29/11/2018 =
* Fix - Text domain fixed.
* Dev - Products - "Add variations" option added.
* Dev - Admin settings restyled: divided into separate ("Categories", "Tags" and "Products") sections (and "Enable section" options added).
* Dev - Plugin renamed from "Payment Gateways per Product Categories for WooCommerce" to "Payment Gateways per Products for WooCommerce".
* Dev - Advanced Options - "Add filter" option added.
* Dev - Code refactoring.
* Dev - Plugin URI updated.

= 1.0.0 - 28/08/2017 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
