NullosAdmin
================
2017-04-22 --> 2018-01-09



NullosAdmin is a module for the [kamille framework](https://github.com/lingtalfi/Kamille).


NullosAdmin aims at providing a general purpose admin.
Modules can hook into the admin and add their functionality, and coexist with other modules.

For instance, nullos admin could host both an e-commerce module and a blog module (their admin counterparts).



The NullosAdmin module uses the Claws system (defined in the Kamille framework).
And so the Claws layout and positions are predefined, and shown on the schema below, representing nullos admin's 
default layout.



[![nullos-admin-default-layout.jpg](http://lingtalfi.com/img/kamille-modules/NullosAdmin/nullos-admin-default-layout.jpg)](http://lingtalfi.com/img/kamille-modules/NullosAdmin/nullos-admin-default-layout.jpg)


As we can see, there is a left pane and a right pane.

The left pane contains the sidebar, which will contain the main menu (every module will put their menu items here),
and the right pane contains the body (position=maincontent), decorated by a header and a footer.

The header part is called topbar and contains two positions: topbar_left and topbar_right.


The topbar_right position usually contains the connected user info.
The footer is just an include (see claws for more nomenclature details): it doesn't contain any position as for now.












