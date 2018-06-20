Badges in nullosAdmin
======================
2018-06-20



Badges allows us to tag users.




Badge and modules
-------------------

Modules should provide the list of badges they use, with a short description, so that a module user can have an overview of the badges
she can assign to her profile.
The badges.txt file should be found at the root of the module for modules which use badges at all (I suggest).

Badge names should always have the following syntax:

- $ModuleName.$typeOfBadge.$badgeIdentifier


The exception is the following badge:

- root

The root badge is provided by the NullosAdmin as an experiment.
It gives to the user all the powers, and in particular the rights to administer other users.





Badge as message identifiers
----------------------------
Originally, badges were meant as permission/authorization badges.
It turns out I never used such capabilities so far, being the only user of the backoffice.

However, I encounter another case: messages.

Some modules need to send messages to the backoffice users, and so I thought I could use
the badge system to accomplish this.



The badge names will be named like this:

- $Module.alert.$messageIdentifier

Example:  Formaway.alert.new_training

The keyword here is alert, it indicates that this badge is dedicated to send/receive messages.
This type of badge basically gives one the ability to read a message, and so nullos users can subscribe
to module messages simply by adding the corresponding badges to their profile.


The benefits of this is:

- you don't have to decide at the global level whether or not to send messages (each user decide for herself)


The main drawback:

- it potentially creates a lot of badges 