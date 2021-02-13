<?php 

namespace KartMax;

use KartMax\Request;
use Symfony\Contracts\Cache\ItemInterface;

class ThrottleMiddleware
{
    public function handle($request)
    {
        $visitorIp = $request->ip();
        $requestBucketCounter = cache()->get("ip-{$visitorIp}", function(ItemInterface $cache) {
            $cache->expiresAfter(1); //time in minutes
            return 0;
        });
        $requestBucket = cache()->getItem("ip-{$visitorIp}");
        $requestBucketCounter = $requestBucket->set($requestBucketCounter+1);
        cache()->save($requestBucketCounter);
        $counter = cache()->get("ip-{$visitorIp}",  function(ItemInterface $cache) {});
        if($counter > 10)
        {
            return jsonResponse(['message' => 'Too Many Requests'], 429);
        }
    }
}