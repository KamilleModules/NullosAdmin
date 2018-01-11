Nullos authentication system
================================
2018-01-11



If the user is not connected, the login page shall be displayed.



In the php session:

```php
- nullos
----- ?user
--------- login: string
--------- rights: array
--------- ...other fields if you need them
```


If the user key DOES NOT EXIST, this means that the user IS NOT CONNECTED.
If it exists, it has to be an array.



The main idea is that modules add what they want in the "rights" key when the user authenticates.
By default, if no module reacts, then NullosUser will deny the privilege.


Then, there is the NullosUser object.

```php
- NullosUser
----- bool isConnected 
----- bool has ( privilege ) 
----- array getRights ( ) 
```


The has method use (kamille) hooks under the hood, so that the responsibility of deciding whether or not
a user has a privilege is delegated to the modules.





Example with imaginary Horse module
----------------------------------------

When the user authenticates, the Horse module adds the horse_admin (for instance) string to the rights array.
The session then looks something like this.

```php
- nullos
----- user
--------- login: string
--------- rights: 
------------- horse_admin
```

Then, in the Horse module code space, somewhere a call to NullosUser::has is made:

```php
NullosUser::has ( can_delete_article )
```

In parallel, the Horse module has registered to the "NullosAdmin_User_hasRight" hook 
provided by Nullos to handle this question.
The Horse module can use the NullosUser::getRights methods to access the connected user's rights.






The badge system
====================
Although nullos is agnostic about how modules handle their permissions system,
it provides a default badge system that modules can embrace, or not.



The badge system is quite easy to understand:
to each profile, any number of badges are assigned.

For instance, user1 has 2 badges from ekom module (ekom_admin and ekom_user),
and 2 badges from module blog (blog_moderator, blog_writer).


Then, modules interpret badges the way they want (maybe a badge will allow to pass
multiple doors, or maybe just one, depending on the module).




Database
=============

Here is the structure of the nullos user in the database.
If we use a database, the nullos.user.login key will contain the id of the db record.

nul_user:
------------
- id
- email: uq
- pass
- avatar
- pseudo
- active: 0|1
- date_created: datetime
- date_last_connexion: datetime|null


Email/pass couple is used to authenticate the user.
Pass is a hash.
Avatar is the user image.
Pseudo is the name by which the user is called in the back office (Hi, $pseudo)
If active is 1, the user is active, if 0, the user is not active and shall not be able to access the backoffice.
Date created: the when this account was created.
Date last connexion: the date when the user connected for the last time.





nul_badge
------------
- id: pk
- name: uq

nul_user_has_badge
------------
- user_id: pk
- badge_id: pk





