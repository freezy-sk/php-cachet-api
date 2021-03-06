<?php

namespace CheckItOnUs\Cachet;

use CheckItOnUs\Cachet\Builders\IncidentQuery;
use CheckItOnUs\Cachet\Exceptions\InvalidVersionException;
use Composer\Semver\Comparator;

class IncidentUpdate extends BaseApiComponent
{
    const SCHEDULED = 0;
    const INVESTIGATING = 1;
    const IDENTIFIED = 1;
    const WATCHING = 3;
    const FIXED = 4;

    /**
     * Dictates the server that the Incident Update relates to.
     *
     * @param \CheckItOnUs\Cachet\Server $server The server
     */
    public static function on(Server $server)
    {
        if (Comparator::lessThanOrEqualTo($server->version(), '2.4')) {
            throw new InvalidVersionException('Server needs to be at least version 2.4 to use this feature.');
        }

        return (new IncidentQuery())
            ->onServer($server);
    }

    /**
     * Hydrates a new instance of an Incident Update.
     *
     * @param array $metadata The metadata
     */
    public function __construct(Server $server, array $metadata = [])
    {
        if (Comparator::lessThanOrEqualTo($server->version(), '2.4')) {
            throw new InvalidVersionException('Server needs to be at least version 2.4 to use this feature.');
        }

        $this->setStatus(self::INVESTIGATING);

        parent::__construct($server, $metadata);
    }

    /**
     * The URL which will be used in order to create a new object.
     *
     * @return string The object's create URL
     */
    public function getApiCreateUrl()
    {
        return static::buildUrl('', [
            'incident' => $this['incident_id'],
        ]);
    }

    /**
     * The URL which will be used in order to update a new object.
     *
     * @return string The object's update URL
     */
    public function getApiUpdateUrl()
    {
        return static::buildUrl(':update', [
            'incident' => $this['incident_id'],
            'update'   => $this['id'],
        ]);
    }

    /**
     * The URL which will be used in order to delete a new object.
     *
     * @return string The object's delete URL
     */
    public function getApiDeleteUrl()
    {
        return static::buildUrl(':update', [
            'incident' => $this['incident_id'],
            'update'   => $this['id'],
        ]);
    }

    /**
     * Gets the base path for the API.
     *
     * @return string The api root path.
     */
    public static function getApiRootPath()
    {
        return '/v1/incidents/:incident/updates';
    }
}
