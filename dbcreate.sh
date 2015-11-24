#!/bin/bash

#Step Create DB instance
echo "Creating DB instance"
mapfile -t result < <(aws rds describe-db-instances --db-instance-identifier itmo-544-sukanya --output table | grep Address | sed "s/|//g" | tr -d ' ' | sed "s/Address//g")

# wait for the DB instance to be available
echo "waiting for the Db instance to be available"
#aws rds wait db-instance-available --db-instance-identifier itmo-544-SN-db 
 
echo "DB instance wait over. It should be Available "
#Create Read replica of the Db instance in the same region
echo "creating read replica"
#aws rds create-db-instance-read-replica --db-instance-identifier itmo-544-SN-dbreplica --source-db-instance-identifier itmo-544-SN-db --db-instance-cass db.t1.micro --availability-zone us-west-2a

# wait for read replica to be available
echo "waiting for read replica to be available"
#aws rds wait db-instance-available --db-instance-identifier itmo-544-SN-dbreplica

echo "============\n". $result . "================";

sudo apt-get install php5-mysql

php ./itmo-544-final/setup.php $result

echo "ALL DONE"

