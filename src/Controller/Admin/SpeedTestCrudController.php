<?php

namespace App\Controller\Admin;

use App\Entity\SpeedTest;
use App\Service\Helper;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use function Symfony\Component\String\u;

class SpeedTestCrudController extends AbstractCrudController
{
    private Helper $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public static function getEntityFqcn(): string
    {
        return SpeedTest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setDefaultSort(['timestamp' => 'DESC'])
            ->setPageTitle(Crud::PAGE_INDEX, '<i class="fas fa-tachometer"></i> Speeds')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('timestamp', 'Date')
                ->formatValue(fn($value, SpeedTest $speedTest) => $speedTest->getTimestamp()->format('d M Y, H:i A')),
            AssociationField::new('server'),
            AssociationField::new('serviceProvider')->formatValue(fn($value) => u($value)->upper()),
            NumberField::new('download')->formatValue(function ($value, SpeedTest $speedTest) {
                return $this->helper->humanSize(round(floatval($speedTest->getDownload())), 1) . "/s";
            }),
            NumberField::new('upload')->formatValue(function ($value, SpeedTest $speedTest) {
                return $this->helper->humanSize(round(floatval($speedTest->getUpload())), 1) . "/s";
            }),
            NumberField::new('bytesSent', 'Sent')->formatValue(fn($value, SpeedTest $speedTest) => $this->helper->humanSize($speedTest->getBytesSent(), 1)),
            NumberField::new('bytesReceived', 'Received')->formatValue(fn($value, SpeedTest $speedTest) => $this->helper->humanSize($speedTest->getBytesReceived(), 1)),
            NumberField::new('ping')->formatValue(fn($value, SpeedTest $speedTest) => number_format($speedTest->getPing(), 1) . ' ms'),
        ];
    }
}
