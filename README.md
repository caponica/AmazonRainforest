Complete Amazon Rainforest API
==============================

This is an attempt to wrap and simplify access to the [Rainforest API](https://rainforestapi.com/) for PHP coders

Installation
============

Install using composer: 

    composer require caponica/amazon-rainforest-api


Accessing the API
=================

Your code will look something like this:

    use CaponicaAmazonRainforest\Client\RainforestClient;
    use CaponicaAmazonRainforest\Request\ProductRequest;

    $config = [ 'api_key' => 'YOUR_API_KEY' ];
    $rfClient = new RainforestClient($config);

    $rfRequests = [
        new ProductRequest(RainforestClient::AMAZON_SITE_USA, 'B001234567'),
        // ... can queue up more - they are sent asynchronously ...
    ];
    $rfRequests = $rfClient->prepareRequestArray($requests); // Optional step. De-duplicates the requests and sets keys you can use for local caching.
    $apiEntities = $rfClient->retrieveProducts($rfRequests);

    foreach ($apiEntities as $key => $rfProduct) {
        // do something with the Product object ...
    }

The API calls return objects with cleaned up data and sane accessors.

Integrating with your own code
==============================

The code in this library uses methods to manipulate its objects. This means that you can extend the Rainforest object classes, then pass your versions through to the API and it will use your versions.

For example: We use Symfony and Doctrine in our own main code base. Our Doctrine classes extend the Rainforest ones:

    namespace Caponica\OurAmazonBundle\Entity;
    
    class RainforestReview extends \CaponicaAmazonRainforest\Entity\RainforestReview {}

Then we work with the Rainforest objects like this:

    // ...
    
    $config = [ 'api_key' => 'YOUR_API_KEY' ];
    $rfClient = new RainforestClient($config);

    $rfRequests = [
        new ProductRequest(RainforestClient::AMAZON_SITE_USA, 'B001234567'),
    ];
    $rfRequests = $rfClient->prepareRequestArray($requests); // De-duplicates the requests and sets keys you can use for local caching.

    $requestsToSendToApi = [];
    $rfEntities = $this->retrieveCachedProductsForMarketAsins(array_keys($requests)); // check to see if we already have local copies of any of the objects we're about to pull from the API
    foreach ($requests as $key => $request) {
        if (array_key_exists($key, $rfEntities)) {
            if ($this->cachedDataIsFresh($rfEntities[$key], $maxAgeHours)) {
                $this->logVerbose("$key using cached data");
                continue;
            } else {
                $this->logVerbose("$key cached data stale, fetching from API");
            }
        } else {
            $rfEntities[$key] = new RainforestProduct(); // instantiate our version the Product object for each request
            $this->logVerbose("$key not cached, fetching from API");
        }
        $requestsToSendToApi[$key] = $request;
    }


    $apiEntities = $rfClient->retrieveProducts($requestsToSendToApi, $rfEntities);
    // In the line above we pass our versions of the entities to the API in the second argument.
    // It will then update them (and use any overridden methods on our version of the classes) 
    // instead of creating new ones using its own objects. 

    foreach ($apiEntities as $key => $rfProduct) {
        // do something with the Product object, e.g. persist() it if it's useful  ...
    }
    $this->em->flush(); // Commit the database changes


Logging
=======

RainforestClient takes an optional Psr\Log\LoggerInterface object in its constructor. If you provide one then you can control how messages are output (or discarded).

See https://github.com/php-fig/log and https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
for more details about setting up a Logger.

A basic Logger (which simply echoes messages), would look like this:

    # EchoLogger.php

    namespace Your\Path;
    
    use Psr\Log\AbstractLogger;
    
    class EchoLogger extends AbstractLogger
    {
        /**
         * Logs with an arbitrary level.
         *
         * @param mixed  $level
         * @param string $message
         * @param array  $context
         *
         * @return void
         */
        public function log($level, $message, array $context = array())
        {
            echo "$message\n";
        }
    }

You then simply pass an EchoLogger instance into the constructor:
    
    use CaponicaAmazonRainforest\Client\RainforestClient;
    use Your\Path\EchoLogger;

    $config = [ ... ];
    $echoLogger = new EchoLogger();
    $rfClient = new RainforestClient($config, $echoLogger);

About the Author
================

Package created and maintained by Christian Morgan.

I'm a well-established Amazon/e-commerce entrepreneur and co-founder of ScaleForEtail. If you are also an Amazon brand owner or seller then you're welcome to [join the ScaleForEtail community](https://facebook.com/groups/scaleforetail/). We organise [webinars](https://app.livestorm.co/scaleforetail/) and live events for the e-commerce community. In short: we bring like-minded people together.

Drop by and check us out today! https://facebook.com/groups/scaleforetail/

Bugs and Feature Requests
=========================

If you find something not working as expected please create an issue on github - or a pull request with a fix! This library is open-source and provided free of charge without warranty of any kind. I hope you find it useful!
