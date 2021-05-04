# Thrive Optimize Apocryphal API

Thrive Optimize is just awesome. BUT... sometimes you need more.

Before we continue, I have to say that my english isn't one of my best abilities. So, hope that you could understand everything.

## Why We need more?

In my use case, not every conversion send people to a page so Thrive can track the conversion. We have offline conversions, we have payments that got a "pending" status prior to get "paid" status.

The point is: sometimes I need to trigger conversion from elsewhere, when I got an conversion API from my payment proccessor for example.

## Just that?

Another point is when we have variable payment values. If people can add more items to his cart, for example. Thrive Optimize just allows me to give a value for each page. But my clients pay differente values and are redirected to the same page!

So, I need to customize the conversion value. I need to get the real conversion value and add it as a conversion.

## You need that too?

If you are asking that, you probably don't need that. This is for people that are already craving for that and know exactly how they will use it.

## How I use that?

It's very simple. To trigg a conversion you need to send to your WP only 3 parameters:

> test_id: the ID of the test itself

> variation_id: the ID of the page/post where the visitor landed

> value: the value of the conversion

But, of course, for your server be able to send those data, it should know those data.

That's why to use the plugin there are 2 main steps:

1. Somewhere you need to send to your server the test_id and `variation_id`. I use a plugin for that, and send it for my payment processor as parameters. When my client pays, I get those data back on the API.

2. Send the data to a endpoint that will call the conversion.

### Step #1

To calling it easily, I do that before:

> use apt\thewhale\thrive_optimize_service as ThriveOptimizeService;

Then I have those methods:

#### is_ab_test

It checks if current page is part of an AB Test. Returns `true` or `false`
> ThriveOptimizeService::is_ab_test()

#### get_current_test_title

Returns the test Title
> ThriveOptimizeService::get_current_test_title()

#### get_current_test_id

Returns the test ID
> ThriveOptimizeService::get_current_test_id()

The variation ID you use...
> get_the_ID()

If `is_ab_test` returns true, them the current ID is an variation ID.

## Step #2

### Get all AB Tests

> GET

> /wp-json/tvo-apo-api/v1/ab-tests

You can send the parameter `test_status` (`running`|`completed`) to filter tests that are currently running or that you already stoped.

### Get specific AB Tests

Just add param test_id. It will return the test data or a 404 if the test doesn't exists.

> /wp-json/tvo-apo-api/v1/ab-tests?test_id=2

# And finally...

### Add Conversion

> x-wwww-form-urlencoded

> POST

> /wp-json/tvo-apo-api/v1/conversion

> params: test_id | variation_id | value

## That's all guys