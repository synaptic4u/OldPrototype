# api.synaptic4u.co.za

2021/05/18

This will be my systems core API.

Still in the DEV phase.

The directories and files ending with backup, go into their own domain or subdomain, 
this will then pull everything from the API domain/sub-domain.

After the initial core is working correctly with the Journal, then the Journal will be moved into it's own namespace as a package.

I will then add a table for all the packages that have been subscribed too along with client hierachy.

Also will be white labelling it and creating a 4/12+ tier privilige hierachy structure:

Privileges: Module/Package

0. Me
1. SysAdmin - Admin over all modules/packages - Full Privileges over all systems
2. Admin - Admin over a particular module - Module / Package Privilege over one or more systems
3. Manager - Limited privileges over module/package
4. User - module/package user

Hierachy: 

0. Me
1. Holding Company
2. Franchise
3. Company
4. Branch
5. Upper Managerial
6. Managerial
7. Department
8. Section Accounts
9. Section IT
10. Section ETC
11. Section ...
12. Section ...
12. Section ...

Need to polish user management - single user
Develop multi-user (Assignment)

USER
-----------------------
-----------------------
User Managed by system:
-----------------------
Is Priviliged Role:
After timeout: Logout user.
Check for multiple concurrent login:
Using IP -> Check on website : Mobile, Laptop, VPN.
IsAdmin: Must update it to see where it's sourced from!!!
Must get privileges.

User Self Managed:
-----------------------
Login: Check that everything is working as expected -> Making Diagrams
Forgot Password: Check that everything is working as expected -> Making Diagrams
Register: Check that everything is working as expected -> Making Diagrams
View: Check that everything is working as expected -> Making Diagrams -> Pass userid through to forms.
Edit: Check that everything is working as expected -> Making Diagrams -> Pass userid through to forms.
Update: Check that everything is working as expected -> Making Diagrams
Delete: Must still add soft delete & hard delete.

REVISIT POPI ACT FOR SYSTEM - Rewrite disclosure, also email user, company, self a copy of it!!!

User Admin Managed:
-----------------------
Add a user
-> User receives email with link, link will have to take the user where they agree on POPI!
-> User will be notified that Admin will be notified if user disagrees.
-> Admin will be notified.

