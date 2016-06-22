<aside class="user-profile-sidebar">
    <div class="user-profile-avatar text-center">
        <img src="/img/300x300.png" alt="Image Alternative text" title="AMaze" />
        <h5>{{$auth['userInfo']['first_name']}} {{$auth['userInfo']['last_name']}}</h5>
    </div>
    <ul class="list user-profile-nav">
        <li><a href="/my-listings"><i class="fa fa-camera"></i>My Listings</a>
        </li>
        <li><a href="/my-requests"><i class="fa fa-camera"></i>Requests</a>
        </li>
        <li><a href="/bookings"><i class="fa fa-clock-o"></i>My Bookings</a>
        </li>
        <li><a href="/logout"><i class="fa fa-clock-o"></i>Sign Out</a>
        </li>
    </ul>
</aside>