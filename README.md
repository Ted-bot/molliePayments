# molliePayments
Created Class and view for mollie payments

Created a class that collects mollie related data from a table in the database

within the class there is a mainquery and subqueries 
the mainquery is used in main part of the view (table), like name, payment status, date created and date completed
the subquery like getTotalPaymentsRows collect based on the payment status (new/paid/expired) all records of a specified account
and shows the amount in the table column 'Total'

The class & view is created for clients that use the kleisteen/claystone application, that have created and send invoices using Mollie Payments
