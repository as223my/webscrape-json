Reflektion
======================
## Webbskrapan

[Till Webbskrapan](http://anniesahlberg.se/Labb1Webbteknik/)

### 1. Vad tror Du vi har för skäl till att spara det skrapade datat i JSON-format?

Json är lättare att förstå och läsa en xml, både för människor och maskiner. 
Json kräver heller inte att slut taggar måste finnas, det blir mindre rum för error. 
Snabbare att använda en xml, och framförallt används Json till väldigt många APIer tex. twitter. 
 
### 2. Olika jämförelsesiter är flitiga användare av webbskrapor. Kan du komma på fler typer av tillämplingar där webbskrapor förekommer?

För hämtning och utbyte av information vid tex forskning och studier. 
Skrapa information som kan läsas offline. (ex Instapaper). 

Mint.com, en app som hjälper dig att spara pengar. Då den skrapar information
från din bank sida och presenterar detta får du en snabb överblick om vart pengarna tar vägen. 

### 3. Hur har du i din skrapning underlättat för serverägaren?

Jag har i mitt curl anrop identifierat vem jag är (med hjälp av mitt användarnamn). 
Jag använder heller inte fler curlanrop en nödvändigt (dvs en per sida). 

### 4. Vilka etiska aspekter bör man fundera kring vid webbskrapning?

Kolla alltid i terms of use innan du skrapar från en sida! Om informationen inte står där fråga ägaren till 
sidan om det är okej att skrapa. Tänk på att servern kan segas ner hos ägaren om för mkt skrapning sker från olika håll. (oändliga while loopar etc)

### 5. Vad finns det för risker med applikationer som innefattar automatisk skrapning av webbsidor? Nämn minst ett par stycken!

Om arkitekturen på sidan du skrapar ifrån ändras, kommer inte skrapningen att fungera som tänkt. 
Detta gäller givetvis om klassnamn etc skulle genomgå ett nambyte med. 
Att man skrapar information som inte är laglig att skrapa. 

### 6. Tänk dig att du skulle skrapa en sida gjord i ASP.NET WebForms. Vad för extra problem skulle man kunna få då?

Varje anrop kräver att man skickar med information så att ASP.NET view state inte ändras.

### 7. Välj ut två punkter kring din kod du tycker är värd att diskutera vid redovisningen. Det kan röra val du gjort, tekniska lösningar eller lösningar du inte är riktigt nöjd med.

 * Jag får massa varningar om ex. DOMDocument::loadHTML(): Tag header invalid in Entity. Har för tillfället tagit bord varningarna med hjälp av libxml_use_internal_errors(true); 
 
 * Ville från början strukturera upp koden till mvc standard, men jag var osäker vart jag skulle placera de olika funktionerna. 
 

### 8. Hitta ett rättsfall som handlar om webbskrapning. Redogör kort för detta.

Ett av de första stora rättsfallen (2003) som involverar webb skrapning var American airlines som stämde företaget FareChase.   
FareChase använde sig av American Airlines webbsida för att skrapa information om dess priser för att använda vidare i sin tjänst som de sedan sålde för att jämföra olika priser på flygningar. 
FareChase överklagade domen (beslutet) och de båda företagen gjorde upp, och överklagningen lades därefter ner. 

Facebook vann även 2009 ett rättsfall om upphovsrätt mot en känd webb skrapa. 

### 9. Känner du att du lärt dig något av denna uppgift?

Jag har lärt mig mycket! Har aldrig skrapat data från andra webbplatser förut, så allt var i princip nytt och kul att genomföra. 
Specielt xpath var lite klurigt, hur jag skulle formulera min fråga för att få rätt information tillbaka. 