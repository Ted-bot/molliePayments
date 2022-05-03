# molliePayments
Created Class and view for mollie payments

Created a class that collects mollie related data from a table in the database

within the class there is a mainquery and subqueries 
the mainquery is used in main part of the view (table), like name, payment status, date created and date completed
the subquery is used when the mainquery is being looped out (foreach)
the mainquery provides a payment id for the function getTotalPaymentRows 
which then collects all records of a specified payment id
and gets previewed in the table column 'Total'

The class & view is created for clients that use the kleisteen/claystone application, that have created and send invoices using Mollie Payments
