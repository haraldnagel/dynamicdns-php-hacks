# dynamicdns-php-hacks
Quick hacks for updating dynamic DNS via PHP scripts.

Both scripts:

* Obtain the IPv4 address from the HTTP environment variable `REMOTE_ADDR` so you don't need to know your own IPv4 address.
* Only perform an update if the IP address has changed.

## nsupdate.php

The first hack just performs a local [`nsupdate(1)`](https://en.wikipedia.org/wiki/Nsupdate) using a BIND key stored locally. It also will update an IPv6 record but it expects that to be passed in via the query (e.g. `nsupdate.php?v6=::1`).

## cfupdate.php

It's a lot easier to just use [CloudFlare's free dynamic DNS service](https://support.cloudflare.com/hc/en-us/articles/200168816-Does-CloudFlare-work-with-Dynamic-DNS-Can-I-update-my-DNS-records-remotely-). This hack just performs an IPv4 update to CloudFlare so you don't need your own authoritative BIND server.

## License

Code released under the MIT license.