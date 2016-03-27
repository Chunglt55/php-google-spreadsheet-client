<?php
namespace GoogleSpreadsheet\Tests\Google\Spreadsheet;

use Google\Spreadsheet\SpreadsheetService;
use Google\Spreadsheet\Spreadsheet;
use Google\Spreadsheet\SpreadsheetFeed;
use Google\Spreadsheet\Exception\BadRequestException;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\DefaultServiceRequest;

class SpreadsheetServiceTest extends TestBase
{
    public function testGetSpreadsheets()
    {
        $this->setServiceRequest("spreadsheet-feed.xml");

        $spreadsheetService = new SpreadsheetService();
        $feed = $spreadsheetService->getSpreadsheets();

        $this->assertTrue($feed instanceof SpreadsheetFeed);
    }

    public function testGetResourceById()
    {
        $this->setServiceRequest("spreadsheet.xml", false);

        $spreadsheetService = new SpreadsheetService();
        $spreadsheet = $spreadsheetService->getResourceById(
            Spreadsheet::class,
            "https://spreadsheets.google.com/feeds/spreadsheets/private/full/tFEgU8ywJkkjcZjG"
        );

        $this->assertTrue($spreadsheet instanceof Spreadsheet);
    }

    /**
     * @expectedException Google\Spreadsheet\Exception\ResourceNotFoundException
     */
    public function testGetResourceByIdException()
    {
        $resourceId = "http://resource";

        $mockRequest = $this->getMockBuilder(DefaultServiceRequest::class)
            ->setMethods(["get"])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo($resourceId)
            )
            ->will($this->throwException(new BadRequestException()));

        ServiceRequestFactory::setInstance($mockRequest);

        $spreadsheetService = new SpreadsheetService();
        $spreadsheet = $spreadsheetService->getResourceById(
            Spreadsheet::class,
            $resourceId
        );
    }
}