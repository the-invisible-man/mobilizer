# Mobilizer

Project is no longer mantained. It's a decently sized platform, backend built in 1 month and frontend in 1 month. Feel free to reuse something that looks helpful!

The mobilizer package is an open-sourced grass roots activism platform built on top the the Laravel Framework. It includes an HTML version of all pages as well as a RESTful API for creating a fancy SPA.

This repository is only the CRUD of the system, for a fully functional platform you will need to pull the Mobilizer-Geo-Tools repository which includes all mathematical algorithms for expanding and manipulating geospatial points as well as Elasticsearch synchronization. You can find this repo [here](https://github.com/the-invisible-man/mobilizer-geo-tools)

## Important Folders
The most relevant parts of the application are located bellow:
* Frontend JS: [Javascript](https://github.com/the-invisible-man/mobilizer/tree/master/public/js/mobilizer) | [HTML Views](https://github.com/the-invisible-man/mobilizer/tree/master/resources/views)
* Backend PHP: [Core Packages](https://github.com/the-invisible-man/mobilizer/tree/master/app/Lib/Packages) | [Controllers](https://github.com/the-invisible-man/mobilizer/tree/master/app/Http/Controllers) | [Migrations](https://github.com/the-invisible-man/mobilizer/tree/master/database/migrations)

## Dependencies

* Elasticsearch 5.3.*
* Redis 3.2.*
* PHP 7.0
* MySQL 5.7

## Contributing

You are free to submit a pull request for bug fixes or new features. Major new features should be discussed prior to building.

## License

The Mobilizer platform and any derivatives from it, shall be strcily use for non commercial purposes only.
