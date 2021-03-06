<?php
/**
 * MembershipHandler.php
 *
 * The MembershipHandler class file.
 *
 * PHP versions 5
 *
 * @author    Alexander Schneider <alexanderschneider85@gmail.com>
 * @copyright 2008-2017 Alexander Schneider
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 * @version   SVN: $id$
 * @link      http://wordpress.org/extend/plugins/user-access-manager/
 */
namespace UserAccessManager\ObjectMembership;

use UserAccessManager\UserGroup\AbstractUserGroup;
use UserAccessManager\UserGroup\AssignmentInformation;
use UserAccessManager\UserGroup\AssignmentInformationFactory;

/**
 * Class MembershipHandler
 *
 * @package UserAccessManager\UserGroup
 */
abstract class ObjectMembershipHandler
{
    /**
     * @var AssignmentInformationFactory
     */
    protected $assignmentInformationFactory;

    /**
     * @var string
     */
    protected $generalObjectType;

    /**
     * @var array
     */
    protected $assignedObjects;

    /**
     * MembershipHandler constructor.
     *
     * @param AssignmentInformationFactory $assignmentInformationFactory
     *
     * @throws \Exception
     */
    public function __construct(
        AssignmentInformationFactory $assignmentInformationFactory
    ) {
        if ($this->generalObjectType === null) {
            throw new MissingObjectTypeException('Missing general object type, Object type must be set.');
        }

        $this->assignmentInformationFactory = $assignmentInformationFactory;
    }

    /**
     * Returns the object name.
     *
     * @param string $objectId
     * @param string $typeName
     *
     * @return string
     */
    abstract public function getObjectName($objectId, &$typeName = '');

    /**
     * Returns the general object type.
     *
     * @return string
     */
    public function getGeneralObjectType()
    {
        return $this->generalObjectType;
    }

    /**
     * Returns the handled objects.
     *
     * @return array
     */
    public function getHandledObjects()
    {
        return [$this->generalObjectType => $this->generalObjectType];
    }

    /**
     * Returns if the object is handled by this handler.
     *
     * @param $objectType
     *
     * @return bool
     */
    public function handlesObject($objectType)
    {
        $objectTypes = $this->getHandledObjects();
        return isset($objectTypes[$objectType]);
    }

    /**
     * Assigns recursive membership to the assignment information object.
     *
     * @param null|AssignmentInformation $assignmentInformation
     * @param array                      $recursiveMembership
     */
    protected function assignRecursiveMembership(
        &$assignmentInformation,
        array $recursiveMembership
    ) {
        if ($assignmentInformation === null) {
            $assignmentInformation = $this->assignmentInformationFactory->createAssignmentInformation();
        }

        $assignmentInformation->setRecursiveMembership($recursiveMembership);
    }

    /**
     * Checks for the access and assigns the recursive membership array if access.
     *
     * @param bool                       $isMember
     * @param array                      $recursiveMembership
     * @param null|AssignmentInformation $assignmentInformation
     *
     * @return bool
     */
    protected function checkAccessWithRecursiveMembership(
        $isMember,
        array $recursiveMembership,
        &$assignmentInformation
    ) {
        if ($isMember === true || count($recursiveMembership) > 0) {
            $this->assignRecursiveMembership($assignmentInformation, $recursiveMembership);
            return true;
        }

        return false;
    }

    /**
     * Returns the assigned objects as array map.
     *
     * @param AbstractUserGroup $userGroup
     * @param string            $objectType
     *
     * @return array
     */
    protected function getSimpleAssignedObjects(AbstractUserGroup $userGroup, $objectType)
    {
        $objects = $userGroup->getAssignedObjects($objectType);

        return array_map(
            function (AssignmentInformation $element) {
                return $element->getType();
            },
            $objects
        );
    }

    /**
     * Checks if the object is a member of the user group.
     *
     * @param AbstractUserGroup          $userGroup
     * @param bool                       $lockRecursive
     * @param string                     $objectId
     * @param null|AssignmentInformation $assignmentInformation
     *
     * @return bool
     */
    abstract public function isMember(
        AbstractUserGroup $userGroup,
        $lockRecursive,
        $objectId,
        &$assignmentInformation = null
    );

    /**
     * Returns the full objects.
     *
     * @param AbstractUserGroup $userGroup
     * @param bool              $lockRecursive
     * @param string            $objectType
     *
     * @return array
     */
    abstract public function getFullObjects(AbstractUserGroup $userGroup, $lockRecursive, $objectType = null);
}
