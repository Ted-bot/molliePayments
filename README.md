# molliePayments
Created Class and view for mollie payments

Created a class that collects mollie related data from a table in the database

Within the class there is a mainquery and subqueries
The mainquery is used in the main part of the view (table), like name, payment status, date created and date completed

The subquery is used when the mainquery is being looped out (foreach)
The mainquery provides a payment id for the function getTotalPaymentRows 
Which then collects all records of a specified payment id
And gets previewed in the table column 'Total'

The class & view is created for clients that use the kleisteen/claystone application, that have created and send invoices using Mollie Payments
