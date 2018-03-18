# oath


##  Given Directions
* Consumes a MySQL slow query log - a sample log has been attached.  Assume the DB schema only has one table named `posts`.
* Display aggregated summary statistics for all SELECT, DELETE, INSERT and UPDATE queries.
* Provide some assurance your code operates as expected.
* BONUS: Update the script to take an argument to aggregate based upon an arbitrary time window.

## Notes
MySQL slow query log may have varying formats. The provided example doesn't lead with a TIME:

## Links
* <https://dev.mysql.com/doc/refman/5.7/en/slow-query-log.html>
