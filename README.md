Assumptions: <br>
Hours are calculated using start of hour at the hour of entry of the vehicle into the parking lot. i.e. Entrance at 06:30 is regarded as entrance at 06:00 <br>
Deregistration is regarded as deletion of the vehicle from the DB while displaying owed amount. Another two possible approaches are using SoftDeletes or having vacated_at with and additional column of paid amount for accounting purposes<br> 


Original requirements:<br>
Parking lot system using different rates for two types of tariffs:<br>
Working hours - 08:00-18:00<br>
Night hours - 18:00-08:00<br>

Types of vehicles and their occupancy:<br>
TypeA - Cars 1 slot<br>
TypeB - Vans 2 slots<br>
TypeC - Truck/Bus 4 slots<br>

Rates:  Day   Night<br>
TypeA - 3/hr,  2/hr<br>
TypeB - 6/hr,  4/hr<br>
TypeC - 12/hr, 8/hr<br>

Discound Cards:<br>
SILVER: 10%<br>
GOLD: 15%<br>
PLATINUM: 20%<br>
Entry without a card is allowed<br>

The parking lot has 200 available slots.<br>

Required Enpoints: <br>
Free spots - getFreeSpots<br>
Amount owed by vehicle to the current moment - getCurrentBillAmount<br>
Entry to the lot - enterVehicle<br>
Vacancy of the lot - checkoutVehicle<br>

Bonus endpoints: (not required by original requirements)<br>
Request a discount card<br>
Invalidate a discount card<br>
Check a card's validity<br>

Bonus - tests<br>
 
NOTES: <br>
Tests use RefreshDatabase trait so handle with care<br>
Amount of free spots as well as discount amounts of cards can be manipulated from ENV. If not defaults are used
