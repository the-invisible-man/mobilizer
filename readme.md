# Mobilizer

Project is no longer mantained. It's a decently sized platform, backend built in 1 month and frontend in 1 month. Feel free to reuse something that looks helpful!

The mobilizer package is an open-sourced car pool web app on top the the Laravel Framework. It includes an HTML version of all pages as well as a RESTful API for creating a fancy SPA.

This repository is only the CRUD of the system, for a fully functional platform you will need to pull the Mobilizer-Geo-Tools repository which includes all mathematical algorithms for expanding and manipulating geospatial points as well as Elasticsearch synchronization. You can find this repo [here](https://github.com/the-invisible-man/mobilizer-geo-tools)

## Important Folders
The most relevant parts of the application are located bellow:
* Frontend JS: [Javascript](https://github.com/the-invisible-man/mobilizer/tree/master/public/js/mobilizer) | [HTML Views](https://github.com/the-invisible-man/mobilizer/tree/master/resources/views)
* Backend PHP: [Core Packages](https://github.com/the-invisible-man/mobilizer/tree/master/app/Lib/Packages) | [Controllers](https://github.com/the-invisible-man/mobilizer/tree/master/app/Http/Controllers) | [Migrations](https://github.com/the-invisible-man/mobilizer/tree/master/database/migrations)

## Ride Search Matching
This web app aims at connecting drivers with passengers who are along the driver's driving route. A naive approach would be to apply the [Haversine formula](https://en.wikipedia.org/wiki/Haversine_formula) from just the driver's starting point, however this method would be inadequate to tackle the problem of matching a driver with a passenger along his driving route. A single proximity calculation would only give us passengers within an n mile radius from the driver's starting point, instead we need along the entire route.

### Radius From Starting Point
![alt tex](https://raw.githubusercontent.com/the-invisible-man/mobilizer/master/map%20-%20Page%201%20(1).png "Haversine")

### Desired Outcome
![alt tex](https://raw.githubusercontent.com/the-invisible-man/mobilizer/master/map%20-%20Page%201.png "Custom")

To do this we will need to know the driver's route ahead of time. Upon the driver entering his location of origin we will use the Google Maps API to determine all the possible routes that the driver could take. The driver will be asked to select a route. Once a route is selected, we get the 'overview_path' from the Maps API. The 'overview_path' is a list of geospatial points that are used to draw the route on the Map Canvas, we will save the raw overview_path in mysql. 

With the overview_path in the database, a script on a cron job will pick up this newly entered ride listing, it'll grab the overview_path and expand it. The process of expansion means that every point in the overview_path is no more than 10 miles apart. If any two points are more than 10 miles apart, the script will calculate a point that is 10 miles apart from the origin, also accounting for the earth's roundness (flat earthers should navigate away at this point). Once the geospatial points are expanded we will save that data into Elasticsearch.

Using elasticsearch we can do a geospatial search for passengers who fall no more than 15 miles from any one of the points of the 'overview_path'. This ultimately allows us to match drivers only with users who are along their driving route.

## User Email Masking and Relay
To keep the user's privacy above all things, this web app takes a similar approach to craigslist for user to user contact. When a prospective passenger selects a ride from the search results, he/she will be given a hashed email address such as `aaAAA9Dmnv09398C6G@relay.mobileizer.com`. The email servers are configured to route to MailGun. With MailGun we can set up an event pusher for any incoming emails to any `*@relay.mobileizer.com` account. MailGun hits this API with the email message, and the class [Postmaster](https://github.com/the-invisible-man/mobilizer/blob/master/app/Lib/Packages/EmailRelay/Postmaster.php) takes over to route the email to the correct destination. Checkout the files under the [EmailRelay](https://github.com/the-invisible-man/mobilizer/tree/master/app/Lib/Packages/EmailRelay) package for more info.

## Dependencies

* Elasticsearch 5.3.*
* Redis 3.2.*
* PHP 7.0
* MySQL 5.7

## Contributing

You are free to submit a pull request for bug fixes or new features. Major new features should be discussed prior to building.

## License

The Mobilizer platform and any derivatives from it, shall be strcily use for non commercial purposes only.
