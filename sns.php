<?php
session_start();
require 'vendor/autoload.php';
$phone = '13126780134';
$issubscribed = 0;
//create sns and configure autoscaling to send notification to sns on alarm
$sns= new Aws\Sns\SnsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
//create topic
$result = $sns->createTopic([
    'Name' => 'Mp2-Topic1', // REQUIRED
]);

$topicarn = $result['TopicArn'];
echo "topic arn value is ----------- $topicarn";
//set topic attributes
$result = $sns->setTopicAttributes([
    'AttributeName' => 'DisplayName', // REQUIRED
    'AttributeValue' => 'TestMP2',
    'TopicArn' => $topicarn, // REQUIRED
]);
//subscribe to the topic using the sms protocol
//need to modify to check if user is subscried, if not then subscribe
//create user table to have user details and the topic arn for whihc they have subscribed
$result = $sns->subscribe([
    'Endpoint' => $phone,
    'Protocol' => 'sms', // REQUIRED
    'TopicArn' => $topicarn, // REQUIRED
]);
//finding the number of subscription
$result = $sns->getTopicAttributes([
    	     'TopicArn' => $topicarn, // REQUIRED
	]);
	echo "number of subscribed and pending";
	echo"subscribed $result['Attributes']['SubscriptionsConfirmed']";
	echo"pending $result['Attributes']['SubscriptionsPending']";
	$subconf =  $result['Attributes']['SubscriptionsConfirmed'];
	$subpen = $result['Attributes']['SubscriptionsPending'];
	
	
//looping until we have the subscriptionarn

	
$i = 0;
for (; ; )
{
	$result = $sns->listSubscriptionsByTopic([
	    'TopicArn' => $topicarn, // REQUIRED
	]);
	echo "waiting subscription confirmation";
	echo "looking endpoint $result['Subscriptions'][$i]['Endpoint']";
	$phone1 = $result['Subscriptions'][$i]['Endpoint'];
	if ($phone == $phone1)
	{
		if (!empty($result['Subscriptions'][$i]['SubscriptionArn'])){
			echo "subscription ARN is not empty";
			$issubscribed=1;
			//connect to db to update the field for the user
			break;
		}
	}
	else
	{
	i++;
	}
	if ( i > 120)
	{
	//if user does not confirm within 2 minutes, break the loop
	break;
	}
}
echo "value of issubscribed $issubscribed";
?>

