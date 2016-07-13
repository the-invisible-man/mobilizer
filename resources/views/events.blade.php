@extends('layouts.app_with_header')

@section('content')
    <div class="container">
        <h1 class="page-title" style="font-size: 40px; font-weight: 700;">Events In Philly</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <aside class="sidebar-left">
                    <div class="sidebar-widget">
                        <h4>Facebook</h4>
                        <div class="fb-like-box" data-href="https://www.facebook.com/SeeYouInPhilly" data-colorscheme="light" data-show-faces="1" data-header="1" data-show-border="1" data-width="233"></div>
                    </div>
                    <div class="sidebar-widget">
                        <h4>Twitter Feed</h4>
                        <div class="twitter" id="twitter"></div>
                    </div>
                </aside>
            </div>
            <div class="col-md-9">
                <!-- START BLOG POST -->
                <article class="article post">
                    <header class="post-header">
                        <div class="fluid-width-video-wrapper" style="padding-top: 50%;">
                            <iframe src="//www.youtube.com/embed/Wu9IJl9jUzM" frameborder="0" allowfullscreen="" id="fitvid280938"></iframe>
                        </div>
                    </header>
                    <div class="post-inner">
                        <h4 class="post-title"><a class="text-darken" href="">The People's Revolution</a></h4>
                        <ul class="post-meta">
                            <li><span class="btn btn-warning btn-xs"><i class="fa fa-star" style="color:white;"></i> Featured Event</span></li>
                            <li><i class="fa fa-calendar"></i><a href="">23 July, 2016</a>
                            </li>
                            <li><i class="fa fa-map-marker"></i><a href="">Arch Street Meeting House</a>
                            </li>
                        </ul>
                        <p class="post-desciption">A demonstration of democracy designed to harness the momentum and influence of the grassroots movement on the eve of the DNC. Join The People’s Revolution Saturday July 23rd, 2016 at the Arch Street Meeting House at 4th and Arch Street in Philadelphia for The People’s Convention.</p><a href="https://thepeoplesrevolution.org/volunteer/" target="_blank">Volunteer</a> | <a href="https://thepeoplesrevolution.org/donate/" target="_blank">Donate</a> | <a href="https://shop.spreadshirt.com/peoples-revolution" target="_blank">Shop</a><p></p><a class="btn btn-small btn-primary" target="_blank" href="https://thepeoplesrevolution.org/the-peoples-convention/">Event Info</a>
                    </div>
                </article>
                <article class="article post">
                    <header class="post-header">
                        <a class="hover-img" href="http://movement4bernie.org/">
                            <img src="/img/events/beyond_bernie.png" alt="Image Alternative text" title="196_365" /><i class="fa fa-link box-icon-# hover-icon round"></i>
                        </a>
                    </header>
                    <div class="post-inner">
                        <h4 class="post-title"><a class="text-darken" href="">Beyond Bernie</a></h4>
                        <ul class="post-meta">
                            <li><i class="fa fa-calendar"></i><a href="">9 - 17 July, 2016</a>
                            </li>
                            <li><i class="fa fa-map-marker"></i><a href="">Multiple Locations</a>
                            </li>
                        </ul>
                        <p class="post-desciption">Join us! Let's get organized to help Bernie win in 2016, stop the right-wing Republicans and counter the corporate dominated Democratic Party establishment. Millions of people are fed up with establishment politics. The momentum behind Bernie Sanders gives us a real chance to gather together everyone who wants to build a real alternative for the 99%.</p><a href="http://movement4bernie.org/about" target="_blank">Mission</a> | <a href="hhttp://movement4bernie.org/donate" target="_blank">Donate</a> | <a href="http://movement4bernie.org/run-all-the-way" target="_blank">Petition</a><p></p><a class="btn btn-small btn-primary" target="_blank" href="http://movement4bernie.org/">Event Info</a>
                    </div>
                </article>
                <article class="article post">
                    <header class="post-header">
                        <a class="hover-img" href="https://www.philly.fyi/">
                            <img src="/img/events/march_on_dnc.png" alt="Image Alternative text" title="196_365" /><i class="fa fa-link box-icon-# hover-icon round"></i>
                        </a>
                    </header>
                    <div class="post-inner">
                        <h4 class="post-title"><a class="text-darken" href="">March On DNC</a></h4>
                        <ul class="post-meta">
                            <li><i class="fa fa-calendar"></i><a href="">23 July, 2016</a>
                            </li>
                            <li><i class="fa fa-map-marker"></i><a href="">1800 Pattison Ave, Philadelphia, Pennsylvania 19145</a>
                            </li>
                        </ul>
                        <p class="post-desciption">When you pull into FDR part look for Bernie yard signs and billboards and you know you'll have reached the location.
                        </p><a href="https://www.philly.fyi/pages/volunteer" target="_blank">Volunteer</a> | <a href="https://www.philly.fyi/pages/newsroom" target="_blank">News Flases</a> <a href="https://www.philly.fyi/products/donation" target="_blank">Donate</a><p></p><a class="btn btn-small btn-primary" target="_blank" href="https://www.philly.fyi/">Event Info</a>
                    </div>
                </article>
                <article class="article post">
                    <header class="post-header">
                        <a class="hover-img" href="http://act.foodandwaterwatch.org/">
                            <img src="/img/events/summit_for_clean_energy.jpg" alt="Image Alternative text" title="196_365" /><i class="fa fa-link box-icon-# hover-icon round"></i>
                        </a>
                    </header>
                    <div class="post-inner">
                        <h4 class="post-title"><a class="text-darken" href="">Summit For a Clean Energy Revolution</a></h4>
                        <ul class="post-meta">
                            <li><i class="fa fa-calendar"></i><a href="">23 July, 2016 - 9:00AM - 6:30PM</a>
                            </li>
                            <li><i class="fa fa-map-marker"></i><a href="">1501 Cherry Street, Philadelphia, PA 19102</a>
                            </li>
                        </ul>
                        <p class="post-desciption">Join us at the Summit for a Clean Energy Revolution on Saturday, July 23 (the day before the March for a Clean Energy Revolution). Be a part of this gathering of people working to ban fracking, keep fossil fuels in the ground, stop dirty energy infrastructure and justly transition to 100% renewable energy. The day will have a mix of educational workshops, organizing skills trainings, and strategy development sessions. Come prepared to acquire new skills and strategies to take back to your local campaigns and to meet people working on similar efforts in their communities across the country</p> <a href="http://act.foodandwaterwatch.org/site/Donation2?df_id=3393&3393.donation=form1" target="_blank">Donate</a><p></p><a class="btn btn-small btn-primary" target="_blank" href="http://act.foodandwaterwatch.org/">Event Info</a>
                    </div>
                </article>
                <article class="article post">
                    <header class="post-header">
                        <a class="hover-img" href="https://www.facebook.com/events/1761467997459515/">
                            <img src="/img/events/democracy_spring.jpg" alt="Image Alternative text" title="196_365" /><i class="fa fa-link box-icon-# hover-icon round"></i>
                        </a>
                    </header>
                    <div class="post-inner">
                        <h4 class="post-title"><a class="text-darken" href="">Democracy Spring at the DNC</a></h4>
                        <ul class="post-meta">
                            <li><i class="fa fa-calendar"></i><a href="">24 - 28 July, 2016 - 5:00PM</a>
                            </li>
                            <li><i class="fa fa-map-marker"></i><a href="">Democratic National Convention, Philadelphia, PA</a>
                            </li>
                        </ul>
                        <p class="post-desciption">Last April, Democracy Spring organized the largest American civil disobedience action of this century, calling on Congress to take action to end to the corruption of big money in politics and ensure free and fair elections. Now, we demand that the Democratic Party implement the political revolution that millions of its voters are calling for and pledge to pass reforms to make this the last corrupt, billionaire-dominated, voter suppression-marred election in our nation.</p> <a href="http://www.democracyspring.org/" target="_blank">Official Website</a><p></p><a class="btn btn-small btn-primary" target="_blank" href="https://www.facebook.com/events/1761467997459515/">Event Info</a>
                    </div>
                </article>
                <h4 style="text-align: center"><a href="https://www.philly.fyi/pages/events" target="_blank">For a full list of events visit <strong>Philly.fyi</strong><br><img src="/img/events/philly_fyi.png"/></a></h4>
                <!-- END BLOG POST -->
            </div>
        </div>
    </div>
@endsection