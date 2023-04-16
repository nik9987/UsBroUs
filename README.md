# UsBroUs
Social media Website (Still in Progress)
This website contains till now a signup, login, and the home page.

Features added in signup page: 
1)Error on empty fields.
2)Email format only allowed in email field.
3)Username should be unique.
4)All the entries will be saved in the database.
5)It has a login button if u r already registered than you can just login

Features of login page:
1)Signup page redirection.
2)Username and password being checked within the database if it valid or not.
3)Added a new column in database ac_status (where 0=not verified,1=verified and 2=Blocked) that will allow user according to their status.
4)As mentioned above, Verfication is required for which we have a mail that will send random codes to the user for verification.
5)Same errors as signup page it will show error if fields are empty or incorrect specifying what the problem is.
6)It has a Forgot Password button that will redirect to forgot password page that will take email for verification and send a code to it. If it is verified then you can change your password if not then redirecting to the same page.

Features of home page:
1)Dynamically changing profile picture and username with actual name of yours on the site.
2)it has a home page icon.
3)Logout button.
4)Edit profile setting which has constraints on each entries.. profile pic size can't be more than 1mb and its type should be jpg,jpeg,png.
 First and last name editable and once changed will be updated over site.
 Email and Gender modification not allowed.(But we can change that by just entering name="" in the tag)
 Username will be checked amongst the existing data from the database and as said earlier it should be unique otherwise not allowed.
 Password can also be modified.

(Will be continued...)
