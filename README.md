# DNS Lookup
Simple library to retrieve DNS records with default OS resolver or specific nameserver.

## Install
Simple, just use composer
```
composer require frankvanhest/dns-lookup
```

## Usage
For a DNS lookup without a specific nameserver:
```
$dnsLookup = new \FrankVanHest\DnsLookup\DnsLookup('domain.com');
```

For a DNS lookup with a specific nameserver:
```
$dnsLookup = new \FrankVanHest\DnsLookup\DnsLookup('domain.com', '8.8.8.8'); // An IP or domain is allowed
```

Get all available records
```
/** @var \FrankVanHest\DnsLookup\DnsRecord $dnsRecord */
foreach ($dnsLookup->getAllRecords() as $dnsRecord) {
    echo $dnsRecord->getName();
    echo $dnsRecord->getType();
    echo $dnsRecord->getValue();
    echo $dnsRecord->getTtl();
    echo $dnsRecord->getPrio();
}
```

Get all available records of a specific type
```
/** @var \FrankVanHest\DnsLookup\DnsRecord $dnsRecord */
foreach ($dnsLookup->getRecordsByType('A') as $dnsRecord) {
    echo $dnsRecord->getName();
    echo $dnsRecord->getType();
    echo $dnsRecord->getValue();
    echo $dnsRecord->getTtl();
    echo $dnsRecord->getPrio();
}
```

## Contribution
If you have any contribution for this project feel free to submit a pull request.

## License
See [License](LICENSE.md)
