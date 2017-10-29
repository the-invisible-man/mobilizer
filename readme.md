# Mobilizer

This project is no longer mantained. It's a decently sized platform, backend built in 1 month and frontend in 1 month. Feel free to reuse something that looks helpful!

The mobilizer package is an opensource car pool web app with a Laravel 5.1 backend, and a custom jQuery frontend. About half of the pages communiacate through a rest API. Others, due to time constraints, are just rendered on the backend. 

This repository is only the CRUD of the system, for a fully functional platform you will need to pull the [Mobilizer-Geo-Tools](https://github.com/the-invisible-man/mobilizer-geo-tools) repository which includes the math functions for expanding and manipulating geospatial points as well as the Elasticsearch sync.

## Important Folders
The most relevant parts of the application are located below:
* Frontend JS: [Javascript](https://github.com/the-invisible-man/mobilizer/tree/master/public/js/mobilizer) | [HTML Views](https://github.com/the-invisible-man/mobilizer/tree/master/resources/views)
* Backend PHP: [Core Packages](https://github.com/the-invisible-man/mobilizer/tree/master/app/Lib/Packages) | [Controllers](https://github.com/the-invisible-man/mobilizer/tree/master/app/Http/Controllers) | [Migrations](https://github.com/the-invisible-man/mobilizer/tree/master/database/migrations)

## Ride Search Matching
This web app aims to connect drivers with carpoolers. While the [Haversine formula](https://en.wikipedia.org/wiki/Haversine_formula) could work, we would be limited to carpoolers in the the radius of the driver's starting location. With this platform drivers can be matched with carpoolers along the driving route, going beyond the radius of the Harversine formula.

### Search Radius From Starting Point
![alt tex](https://raw.githubusercontent.com/the-invisible-man/mobilizer/master/map%20-%20Page%201%20(1).png "Haversine")

### Ideal Search Area
![alt tex](https://raw.githubusercontent.com/the-invisible-man/mobilizer/master/map%20-%20Page%201.png "Custom")

To do this we will need to know the driver's route ahead of time. Upon the driver entering his location of origin we will use the Google Maps API to determine all the possible routes that the driver could take. The driver will be asked to select a route. Once a route is selected, we get the 'overview_path' from the Maps API. The 'overview_path' is a list of geospatial points that are used to draw the route on the Map Canvas, we will save the raw overview_path in mysql. 

With the overview_path in the database, a script on a cron job will pick up this newly entered ride listing, it'll grab the overview_path and expand it. The process of expansion means that every point in the overview_path is no more than 10 miles apart. If any two points are more than 10 miles apart, the script estimate points in between, also accounting for the earth's roundness (flat earthers should navigate away at this point). Once expanded the points are stored in Elasticsearch.

Using elasticsearch we can do a geospatial search for passengers who fall no more than 15 miles from any one of the points saved in ES. This ultimately allows us to match drivers only with users who are along their driving route.

## User Email Masking and Relay
To conceil each party's email address this web app takes a similar approach to craigslist for email masking. When a carpooler creates a ew booking request, the booking will be assigned a random email handle like `aaAAA9Dmnv09398C6G@relay.mobileizer.com`. The email servers are configured to route to MailGun. With MailGun we can set up an event pusher for any incoming emails to any `*@relay.mobileizer.com` account. MailGun hits this API with the email message, and the class [Postmaster](https://github.com/the-invisible-man/mobilizer/blob/master/app/Lib/Packages/EmailRelay/Postmaster.php) takes over to route the email to the correct destination. Checkout the files under the [EmailRelay](https://github.com/the-invisible-man/mobilizer/tree/master/app/Lib/Packages/EmailRelay) package for more info.

## Dependencies

* Elasticsearch 5.3.*
* Redis 3.2.*
* PHP 7.0
* MySQL 5.7

## Contributing

You are free to submit a pull request for bug fixes or new features. Major new features should be discussed prior to building.

## License

The Mobilizer platform and any derivatives from it, shall be strcily use for non commercial purposes only. Yeah my bad.
