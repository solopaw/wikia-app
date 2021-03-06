<?php
/**
 * Topic
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swaagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * curation-cms
 *
 * No description provided (generated by Swagger Codegen https://github.com/swagger-api/swagger-codegen)
 *
 * OpenAPI spec version: 0.1.1-SNAPSHOT
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\CurationCMS\Models;

use \ArrayAccess;

/**
 * Topic Class Doc Comment
 *
 * @category    Class
 * @package     Swagger\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Topic implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'Topic';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'id' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'color' => 'string',
        'image' => '\Swagger\Client\CurationCMS\Models\Image',
        'followers_count' => 'int',
        'is_followed' => 'bool'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'id' => null,
        'name' => null,
        'slug' => null,
        'color' => null,
        'image' => null,
        'followers_count' => 'int64',
        'is_followed' => null
    ];

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'id' => 'id',
        'name' => 'name',
        'slug' => 'slug',
        'color' => 'color',
        'image' => 'image',
        'followers_count' => 'followersCount',
        'is_followed' => 'isFollowed'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'id' => 'setId',
        'name' => 'setName',
        'slug' => 'setSlug',
        'color' => 'setColor',
        'image' => 'setImage',
        'followers_count' => 'setFollowersCount',
        'is_followed' => 'setIsFollowed'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'id' => 'getId',
        'name' => 'getName',
        'slug' => 'getSlug',
        'color' => 'getColor',
        'image' => 'getImage',
        'followers_count' => 'getFollowersCount',
        'is_followed' => 'getIsFollowed'
    ];

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    public static function setters()
    {
        return self::$setters;
    }

    public static function getters()
    {
        return self::$getters;
    }

    

    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['name'] = isset($data['name']) ? $data['name'] : null;
        $this->container['slug'] = isset($data['slug']) ? $data['slug'] : null;
        $this->container['color'] = isset($data['color']) ? $data['color'] : null;
        $this->container['image'] = isset($data['image']) ? $data['image'] : null;
        $this->container['followers_count'] = isset($data['followers_count']) ? $data['followers_count'] : null;
        $this->container['is_followed'] = isset($data['is_followed']) ? $data['is_followed'] : false;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        return true;
    }


    /**
     * Gets id
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets name
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets slug
     * @return string
     */
    public function getSlug()
    {
        return $this->container['slug'];
    }

    /**
     * Sets slug
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->container['slug'] = $slug;

        return $this;
    }

    /**
     * Gets color
     * @return string
     */
    public function getColor()
    {
        return $this->container['color'];
    }

    /**
     * Sets color
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->container['color'] = $color;

        return $this;
    }

    /**
     * Gets image
     * @return \Swagger\Client\CurationCMS\Models\Image
     */
    public function getImage()
    {
        return $this->container['image'];
    }

    /**
     * Sets image
     * @param \Swagger\Client\CurationCMS\Models\Image $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->container['image'] = $image;

        return $this;
    }

    /**
     * Gets followers_count
     * @return int
     */
    public function getFollowersCount()
    {
        return $this->container['followers_count'];
    }

    /**
     * Sets followers_count
     * @param int $followers_count
     * @return $this
     */
    public function setFollowersCount($followers_count)
    {
        $this->container['followers_count'] = $followers_count;

        return $this;
    }

    /**
     * Gets is_followed
     * @return bool
     */
    public function getIsFollowed()
    {
        return $this->container['is_followed'];
    }

    /**
     * Sets is_followed
     * @param bool $is_followed
     * @return $this
     */
    public function setIsFollowed($is_followed)
    {
        $this->container['is_followed'] = $is_followed;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}


