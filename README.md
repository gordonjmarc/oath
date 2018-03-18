# Tumblr SRE-Database Take Home Exercise


##  Given Directions
* Consumes a MySQL slow query log - a sample log has been attached.  Assume the DB schema only has one table named `posts`.
* Display aggregated summary statistics for all SELECT, DELETE, INSERT and UPDATE queries.
* Provide some assurance your code operates as expected.
* BONUS: Update the script to take an argument to aggregate based upon an arbitrary time window.

## Implementation Notes

MySQL slow query log may have varying formats. Each Query Entry is composed of some headers, the query itself, and a timestamp

An entry provided in example looks like this:

```
# User@Host: AppUser[AppUser] @  [10.10.231.172]  Id: 5795966175
# Schema: myawesomeapp  Last_errno: 0 Killed: 0
# Query_time: 1.609551083994948  Lock_time: 0.0024082536056373514  Rows_sent: 1  Rows_examined: 18350  Rows_affected: 0
# Bytes_sent: 0
SET timestamp=1521216600;
SELECT * FROM posts WHERE user_id = 10276 AND state = 0;

```

An issue i've noticed is that Headers aren't always the same, most around the web look more similar to this:

```
# Time: 150224 18:31:56
# User@Host: app_main[app_main] @ localhost [127.0.0.1]
# Query_time: 1.671918  Lock_time: 0.000000 Rows_sent: 164  Rows_examined: 164
SET timestamp=1424802716;
SHOW TABLES FROM `app`;
```

Notice that there is a Time header here, but not in example provided in the assignment. Since entries may be variable in real life, it makes parsing a bit challenging, I shall assume the standard provided can be accounted for in this implementation, and entry format should require regular expression allowing to capture all contents of entry. 

**I assume User@Host is the first header, and the final part of the statement is SQL statement itself.**
**I also assume the metrics provided in the first example are the only interesting ones I can report upon in this implementation**

## Links
* <https://dev.mysql.com/doc/refman/5.7/en/slow-query-log.html>
