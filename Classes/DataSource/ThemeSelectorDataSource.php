<?php
declare(strict_types=1);

namespace Sitegeist\Turncoat\DataSource;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Package\PackageInterface;
use Neos\Flow\Package\PackageManager;
use Neos\Neos\Service\DataSource\AbstractDataSource;

class ThemeSelectorDataSource extends AbstractDataSource
{
    /**
     * @var PackageManager
     * @Flow\Inject
     */
    protected $packageManager;

    /**
     * @var string
     */
    protected static $identifier = "sitegeist_turncoat_themes";

    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        $packages = $this->packageManager->getFilteredPackages('available', 'neos-themes');
        return array_reduce(
            $packages,
            function (array $carry, PackageInterface $package) {
                if ($package instanceof FlowPackageInterface) {
                    $name = $package->getComposerManifest('description');
                    if (empty($name)) {
                        $name = $package->getPackageKey();
                    }
                    $carry[] = [
                        "value" => $package->getPackageKey(),
                        "label" => $name
                    ];
                }
                return $carry;
            },
            [],
        );
    }

}
