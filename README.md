# Generation of php classes from xsd files

We use https://github.com/goetas-webservices/xsd2php to automagically
generate php classes from the xsd files from the XBeteiligung specification.

# Usage
How to use it is documented in tests like testReadBeteiligung2PlanungBeteiligungNeuOK0410()


# Create php classes manually

To manually create the php classes and metadata you need to perform 
following steps

``
[pathToAddon]/vendor/bin/xsd2php convert config/xsd2php.yml Resources/xsd/*.xsd
``

XML messages could automatically be casted to php objects by using the
JMS-Serializer-Bundle http://jmsyst.com/bundles/JMSSerializerBundle. It is
also easily possible to cast php classes back to xml!
