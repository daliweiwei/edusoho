<?php

namespace Tests\Unit\User\Service;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\FileToolkit;
use AppBundle\Common\ReflectionUtils;
use Biz\BaseTestCase;
use Biz\User\CurrentUser;
use Biz\User\Dao\UserDao;
use Biz\User\Service\UserService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class UserServiceTest extends BaseTestCase
{
    public function testGetUserIdsByKeyword()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
            'verifiedMobile' => '13967340620',
        ];
        $registeredUser1 = $this->getUserService()->register($userInfo);

        $userInfo = [
            'nickname' => 'test_nickname1',
            'password' => 'test_password',
            'email' => 'edusoho@edusoho.com',
            'verifiedMobile' => '13967340622',
            'mobile' => '13967340623',
        ];
        $registeredUser2 = $this->getUserService()->register($userInfo);

        $user = $this->getUserService()->getUserIdsByKeyword('test_nickname');
        $this->assertTrue(in_array($registeredUser1['id'], $user));

        $user = $this->getUserService()->getUserIdsByKeyword('test_email');
        $this->assertTrue(!in_array($registeredUser1['id'], $user));

        $user = $this->getUserService()->getUserIdsByKeyword('edusoho@edusoho.com');
        $this->assertTrue(in_array($registeredUser2['id'], $user));

        $user = $this->getUserService()->getUserIdsByKeyword('13967340622');
        $this->assertTrue(in_array($registeredUser2['id'], $user));

        //注册时verifiedMobile 需要和mobile一致，其他参数会被覆盖
        $user = $this->getUserService()->getUserIdsByKeyword('13967340623');
        $this->assertFalse(in_array($registeredUser2['id'], $user));
    }

    /**
     * @group current
     */
    public function testRegister()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);

        $this->assertEquals($userInfo['nickname'], $registeredUser['nickname']);
        $this->assertEquals($userInfo['email'], $registeredUser['email']);
        $this->assertTrue($this->getUserService()->verifyPassword($registeredUser['id'], $userInfo['password']));

        /*default value Test*/
        $this->assertEquals('default', $registeredUser['type']);
        $this->assertEquals(0, $registeredUser['point']);
        $this->assertEquals(0, $registeredUser['coin']);
        $this->assertEquals(0, $registeredUser['locked']);
        $this->assertEquals(0, $registeredUser['loginTime']);
        $this->assertEquals(0, $registeredUser['emailVerified']);
        $this->assertEquals(['ROLE_USER'], $registeredUser['roles']);
    }

    public function testRegisterByNotDefault()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
            'token' => ['userId' => 999, 'token' => 'token', 'expiredTime' => strtotime('+1 day')],
            'type' => 'qq',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);

        $this->assertEquals($userInfo['nickname'], $registeredUser['nickname']);
        $this->assertEquals($userInfo['email'], $registeredUser['email']);
        $this->assertFalse($this->getUserService()->verifyPassword($registeredUser['id'], $userInfo['password']));

        /*default value Test*/
        $this->assertEquals('qq', $registeredUser['type']);
        $this->assertEquals(0, $registeredUser['point']);
        $this->assertEquals(0, $registeredUser['coin']);
        $this->assertEquals(0, $registeredUser['locked']);
        $this->assertEquals(0, $registeredUser['loginTime']);
        $this->assertEquals(0, $registeredUser['emailVerified']);
        $this->assertEquals(['ROLE_USER'], $registeredUser['roles']);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testRegisterWithErrorEmail()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@error_email.com',
        ];
        $this->getUserService()->register($userInfo);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testRegisterWithRegistedNickname()
    {
        $user1Info = [
            'nickname' => 'testuser1',
            'password' => 'test_password',
            'email' => 'test_email@email1.com',
        ];
        $this->getUserService()->register($user1Info);

        $user2Info = [
            'nickname' => 'testuser1',
            'password' => 'test_password',
            'email' => 'test_email@email2.com',
        ];
        $this->getUserService()->register($user2Info);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testRegisterWithRegistedEmail()
    {
        $user1Info = [
            'nickname' => 'testuser1',
            'password' => 'test_password',
            'email' => 'test_email@registerdemail.com',
        ];
        $this->getUserService()->register($user1Info);

        $user2Info = [
            'nickname' => 'testuser2',
            'password' => 'test_password',
            'email' => 'test_email@registerdemail.com',
        ];
        $this->getUserService()->register($user2Info);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testRegisterWithErrorNickname1()
    {
        $this->getUserService()->register([
            'nickname' => 'test_user nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ]);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testRegisterWithErrorNickname2()
    {
        $this->getUserService()->register([
            'nickname' => 'user|!@2',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ]);
    }

    public function testGetUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals($registeredUser, $foundUser);

        $foundUser = $this->getUserService()->getUser(999);
        $this->assertNull($foundUser);
    }

    public function testGetUserAndProfileNull()
    {
        $user = $this->getUserService()->getUserAndProfile(999);
        $this->assertNull($user);
    }

    public function testGetUserAndProfile()
    {
        $id = 2;
        $this->mockBiz('User:UserDao', [
            [
                'functionName' => 'get',
                'withParams' => [$id],
                'returnValue' => [
                    'id' => $id,
                ],
            ],
        ]);

        $this->mockBiz('User:UserProfileDao', [
            [
                'functionName' => 'get',
                'withParams' => [$id],
                'returnValue' => [
                    'id' => $id,
                    'name' => 'test',
                ],
            ],
        ]);

        $expected = [
            'id' => $id,
            'name' => 'test',
        ];

        $user = $this->getUserService()->getUserAndProfile($id);
        $this->assertEquals($expected, $user);
    }

    public function testGetUserByNickname()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $foundUser = $this->getUserService()->getUserByNickname($registeredUser['nickname']);
        $this->assertEquals($registeredUser, $foundUser);

        $foundUser = $this->getUserService()->getUserByNickname('not_exist_nickname');
        $this->assertNull($foundUser);
    }

    public function testGetUnDstroyedUserByNickname()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $user = $this->getUserService()->getUnDstroyedUserByNickname($registeredUser['nickname']);
        $this->assertEquals($registeredUser, $user);

        $this->getUserDao()->update($registeredUser['id'], ['destroyed' => 1]);
        $user = $this->getUserService()->getUnDstroyedUserByNickname($registeredUser['nickname']);
        $this->assertNull($user);
    }

    public function testGetUserByUUID()
    {
        $registeredUser = $this->createFromUser();

        $foundUser = $this->getUserService()->getUserByUUID($registeredUser['uuid']);

        $this->assertArrayEquals($registeredUser, $foundUser);
    }

    public function testGetUserByLoginField()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
            'verifiedMobile' => '13777868634',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $keyword = '13777868634';
        $result = $this->getUserService()->getUserByLoginField($keyword);
        $this->assertEquals($result['id'], $registeredUser['id']);
        $keyword = 'test_email@email.com';
        $result = $this->getUserService()->getUserByLoginField($keyword);
        $this->assertEquals($result['id'], $registeredUser['id']);
        $keyword = 'test_nickname';
        $result = $this->getUserService()->getUserByLoginField($keyword);
        $this->assertEquals($result['id'], $registeredUser['id']);

        $keyword = 'test_email@email.com';
        $this->getUserDao()->update($registeredUser['id'], ['type' => 'system']);
        $result = $this->getUserService()->getUserByLoginField($keyword);
        $this->assertNull($result);

        $this->getUserDao()->update($registeredUser['id'], ['type' => 'import', 'destroyed' => 1]);
        $result = $this->getUserService()->getUserByLoginField($keyword, true);
        $this->assertNull($result);
    }

    public function testGetUserByVerifiedMobile()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
            'verifiedMobile' => '13777868634',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $keyword = '13777868634';
        $result = $this->getUserService()->getUserByLoginField($keyword);
        $this->assertEquals($result['id'], $registeredUser['id']);

        $result = $this->getUserService()->getUserByVerifiedMobile('');
        $this->assertNull($result);
    }

    public function testGetUserByEmail()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $foundUser = $this->getUserService()->getUserByEmail('test_email@email.com');
        $this->assertEquals($registeredUser, $foundUser);

        $foundUser = $this->getUserService()->getUserByEmail('not_exist_email@user.com');
        $this->assertNull($foundUser);
    }

    public function testGetUserByScrmUuid()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $updatedUser = $this->getUserService()->setUserScrmUuid($registeredUser['id'], '12345');
        $res = $this->getUserService()->getUserByScrmUuid('12345');
        self::assertEquals($registeredUser['id'], $res['id']);
        self::assertEquals($updatedUser['scrmUuid'], $res['scrmUuid']);
    }

    public function testGetUserByEmailWithEmptyEmail()
    {
        $foundUser = $this->getUserService()->getUserByEmail('');
        $this->assertNull($foundUser);
    }

    public function testFindUsersByIds()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $foundUsers = $this->getUserService()->findUsersByIds([$user1['id'], $user2['id']]);

        $foundUserIds = array_keys($foundUsers);
        $this->assertEquals(2, count($foundUserIds));
        $this->assertContains($user1['id'], $foundUserIds);
        $this->assertContains($user2['id'], $foundUserIds);

        $foundUsers = $this->getUserService()->findUsersByIds([$user1['id'], $user2['id'], 99999]);
        $foundUserIds = array_keys($foundUsers);

        $this->assertEquals(2, count($foundUserIds));
        $this->assertContains($user1['id'], $foundUserIds);
        $this->assertContains($user2['id'], $foundUserIds);
        $this->assertNotContains(99999, $foundUserIds);

        $foundUsers = $this->getUserService()->findUsersByIds([99999]);
        $this->assertEmpty($foundUsers);
    }

    /**
     * @group tmp
     */
    public function testSearchUsers()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $conditions = [
            'nickname' => 'user1',
        ];
        $orderBy = ['createdTime' => 'ASC'];
        $result = $this->getUserService()->searchUsers($conditions, $orderBy, 0, 20);
        $this->assertEquals(1, count($result));
    }

    public function testChangeRawPassword()
    {
        $user1 = $this->createUser('user1');

        $result = $this->getUserService()->changeRawPassword($user1['id'], ['password' => 'rawpass']);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Biz\Common\CommonException
     */
    public function testChangeRawPasswordWithEmptyRawPassword()
    {
        $user1 = $this->createUser('user1');

        $this->getUserService()->changeRawPassword($user1['id'], []);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testChangeRawPasswordWithEmptyUser()
    {
        $user1 = $this->createUser('user1');

        $this->getUserService()->changeRawPassword($user1['id'] + 100, ['password' => 'rawpass']);
    }

    public function testSearchUserProfileCount()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $result = $this->getUserService()->searchUserProfileCount(['ids' => [$user1['id'], $user2['id']]]);

        $this->assertEquals(2, $result);
    }

    public function testSearchApprovalsCount()
    {
        $user1 = $this->createUser('user1');
        $this->createApproval($user1['id']);
        $count = $this->getUserService()->searchApprovalsCount(['userId' => $user1['id']]);
        $this->assertEquals(1, $count);
    }

    public function testFindUserProfilesByIds()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $foundUserProfiles = $this->getUserService()->findUserProfilesByIds([$user1['id'], $user2['id']]);
        $userProfileIds = array_keys($foundUserProfiles);
        $this->assertEquals(2, count($foundUserProfiles));
        $this->assertContains($user1['id'], $userProfileIds);
        $this->assertContains($user2['id'], $userProfileIds);

        $foundUserProfiles = $this->getUserService()->findUserProfilesByIds([$user1['id'], $user2['id'], 999]);
        $userProfileIds = array_keys($foundUserProfiles);
        $this->assertEquals(2, count($foundUserProfiles));
        $this->assertContains($user1['id'], $userProfileIds);
        $this->assertContains($user2['id'], $userProfileIds);

        $foundUserProfiles = $this->getUserService()->findUserProfilesByIds([999]);
        $this->assertEmpty($foundUserProfiles);
    }

    public function testSearchUserProfiles()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $result = $this->getUserService()->searchUserProfiles([], ['id' => 'DESC'], 0, 10);
        $this->assertCount(3, $result);

        $result = $this->getUserService()->searchUserProfiles(['ids' => [$user1['id'], $user2['id']]], ['id' => 'DESC'], 0, 10);
        $this->assertCount(2, $result);
        $this->assertEquals($user2['id'], $result[0]['id']);
        $this->assertEquals($user1['id'], $result[1]['id']);
    }

    /**
     * @group current
     */
    public function testSearchUsersWithOneParameter()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $foundUsers = $this->getUserService()->searchUsers(['nickname' => 'user1'], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(1, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers(['roles' => 'ROLE_USER'], ['createdTime' => 'DESC'], 0, 10);
        // 还有一个初始化用户， admin@admin.com
        $this->assertEquals(3, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers(['loginIp' => ''], ['createdTime' => 'DESC'], 0, 10);
        // 还有一个初始化用户， admin@admin.com
        $this->assertEquals(3, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers(['nickname' => 'user'], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(2, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers(['email' => 'user1@user1.com'], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(1, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers(['email' => 'user2@user2.com'], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(1, count($foundUsers));
    }

    public function testSearchUsersWithOneParamterAndResultEqualsEmpty()
    {
        $foundUsers = $this->getUserService()->searchUsers(['nickname' => 'user1'], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEmpty($foundUsers);

        $foundUsers = $this->getUserService()->searchUsers(['roles' => 'ROLE_USER'], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(1, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers(['loginIp' => ''], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(1, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers(['nickname' => 'user'], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(0, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers(['email' => 'user1@user1.com'], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(0, count($foundUsers));
    }

    public function testSearchUsersWithMultiParameter()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $foundUsers = $this->getUserService()->searchUsers([
            'nickname' => 'user1',
            'roles' => 'ROLE_USER',
            'loginIp' => '',
            'email' => 'user1@user1.com',
        ], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(1, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers([
            'roles' => 'ROLE_USER',
            'loginIp' => '',
            'nickname' => 'user',
            'email' => 'user1@user1.com',
        ], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(1, count($foundUsers));
    }

    public function testSearchUsersWithMultiParameterAndResultEqualsEmpty()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $foundUsers = $this->getUserService()->searchUsers([
            'nickname' => 'user1',
            'roles' => 'ROLE_USER',
            'loginIp' => '',
            'email' => 'user2@user2.com',
        ], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(0, count($foundUsers));

        $foundUsers = $this->getUserService()->searchUsers([
            'nickname' => 'user2',
            'roles' => 'ROLE_ADMIN',
            'loginIp' => '',
            'email' => 'user1@user1.com',
        ], ['createdTime' => 'DESC'], 0, 10);
        $this->assertEquals(0, count($foundUsers));
    }

    /**
     * @group tmp
     */
    public function testCountUsers()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $foundUserCount = $this->getUserService()->countUsers(['nickname' => 'user1']);
        $this->assertEquals(1, $foundUserCount);
        $foundUserCount = $this->getUserService()->countUsers(['roles' => '|ROLE_USER|']);
        $this->assertEquals(3, $foundUserCount);
        $foundUserCount = $this->getUserService()->countUsers(['email' => 'user1@user1.com']);
        $this->assertEquals(1, $foundUserCount);
    }

    public function testSearchUserCountWithZeroResult()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $foundUserCount = $this->getUserService()->countUsers(['nickname' => 'not_exist_nickname']);
        $this->assertEquals(0, $foundUserCount);

        $currentUser = $this->getCurrentUser();
        $this->getUserService()->changeUserRoles($currentUser['id'], ['ROLE_USER']);
        $foundUserCount = $this->getUserService()->countUsers(['roles' => '|ROLE_ADMIN|']);
        $this->assertEquals(0, $foundUserCount);

        $foundUserCount = $this->getUserService()->countUsers(['email' => 'not_exist_email@user.com']);
        $this->assertEquals(0, $foundUserCount);
        $foundUserCount = $this->getUserService()->countUsers(['loginIp' => '192.168.0.1']);
        $this->assertEquals(0, $foundUserCount);
    }

    public function testSetEmailVerified()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->assertEquals(0, $registeredUser['emailVerified']);

        $this->getUserService()->setEmailVerified($registeredUser['id']);
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(1, $foundUser['emailVerified']);

        $this->getUserService()->setEmailVerified($registeredUser['id']);
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(1, $foundUser['emailVerified']);
    }

    public function testSetFaceRegistered()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->assertEquals(0, $registeredUser['faceRegistered']);

        $this->getUserService()->setFaceRegistered($registeredUser['id']);
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(1, $foundUser['faceRegistered']);

        $this->getUserService()->setFaceRegistered($registeredUser['id']);
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(1, $foundUser['faceRegistered']);
    }

    public function testChangeNickname()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeNickname($registeredUser['id'], 'hello123');
        $result = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals($result['nickname'], 'hello123');
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testChangeNicknameOne()
    {
        $user = null;
        $this->getUserService()->changeNickname($user['id'], 'hello123');
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testChangeNicknameTwo()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeNickname($registeredUser['id'], 'hell_!!o123');
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testChangeNicknameThree()
    {
        $user = $this->createUser('user');
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeNickname($registeredUser['id'], 'user');
    }

    public function testChangeEmail()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeEmail($registeredUser['id'], 'change@change.com');
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals('change@change.com', $foundUser['email']);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testChangeEmailWithErrorEmailFormat1()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeEmail($registeredUser['id'], 'change@ch_ange.com');
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testChangeEmailWithErrorEmailFormat2()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeEmail($registeredUser['id'], 'changechange.com');
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testChangeEmailWithExistEmail()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->changeEmail($user1['id'], 'user2@user2.com');
    }

    public function testIsEmailAvaliable()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $user = $this->getUserService()->register($userInfo);
        $result = $this->getUserService()->isEmailAvaliable('test@user.com');
        $this->assertTrue($result);
        $result = $this->getUserService()->isEmailAvaliable('test_email@email.com');
        $this->assertFalse($result);
        $result = $this->getUserService()->isEmailAvaliable('');
        $this->assertFalse($result);
    }

    public function testIsNicknameAvaliable()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $this->getUserService()->register($userInfo);

        $result = $this->getUserService()->isNicknameAvaliable('anothernickname');
        $this->assertTrue($result);
        $result = $this->getUserService()->isNicknameAvaliable('test_nickname');
        $this->assertFalse($result);
        $result = $this->getUserService()->isNicknameAvaliable('');
        $this->assertFalse($result);
    }

    public function testIsMobileAvaliable()
    {
        $result = $this->getUserService()->isMobileAvaliable('');
        $this->assertFalse($result);
        $result = $this->getUserService()->isMobileAvaliable('13777868634');
        $this->assertTrue($result);
    }

    public function testChangePassword()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->assertTrue($this->getUserService()->verifyPassword($registeredUser['id'], $userInfo['password']));

        $this->getUserService()->changePassword($registeredUser['id'], 'new_password');
        $changePasswordedUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertTrue($this->getUserService()->verifyPassword($changePasswordedUser['id'], 'new_password'));
    }

    /**
     * @expectedException \Biz\Common\CommonException
     */
    public function testChangePasswordTwice()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changePassword($registeredUser['id'], '');
    }

    /**
     * @expectedException \Biz\User\UserException
     * @expectedExceptionMessage exception.user.password_invalid
     */
    public function testChangePasswordInvalidException()
    {
        $user = $this->createUser('user');
        $this->getUserService()->changePassword($user['id'], 'test password invalid');
    }

    /**
     * @expectedException \Biz\Common\CommonException
     */
    public function testChangePayPasswordOne()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changePayPassword($registeredUser['id'], '');
    }

    /**
     * @expectedException \Biz\User\UserException
     * @expectedExceptionMessage exception.user.not_found
     */
    public function testChangePayPasswordWithUserNotFound()
    {
        $this->getUserService()->changePayPassword(233, 'newpassword');
    }

    public function testChangePayPasswordTwice()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $newPayPassword = '12345asd';
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changePayPassword($registeredUser['id'], $newPayPassword);

        $user = $this->getUserService()->getUser($registeredUser['id']);

        $expectedPassword = $this->getPasswordEncoder()->encodePassword($newPayPassword, $user['payPasswordSalt']);
        $this->assertEquals($expectedPassword, $user['payPassword']);
    }

    public function testIsMobileUnique()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
            'verifiedMobile' => '13777868634',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $result = $this->getUserService()->isMobileUnique('13777868634');
        $this->assertFalse($result);
        $result = $this->getUserService()->isMobileUnique('18777868634');
        $this->assertTrue($result);
    }

    public function testChangeMobileOne()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
            'verifiedMobile' => '13777868634',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $result = $this->getUserService()->changeMobile($registeredUser['id'], '18257739598');
        $this->assertTrue($result);
    }

    /**
     * @expectedException \Biz\Common\CommonException
     */
    public function testChangeMobileTwice()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
            'verifiedMobile' => '13777868634',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $result = $this->getUserService()->changeMobile($registeredUser['id'], '');
    }

    /**
     * @expectedException \Biz\User\UserException
     * @expectedExceptionMessage exception.user.not_found
     */
    public function testChangeMobileWithUserNotFoundException()
    {
        $this->getUserService()->changeMobile(233, '12312321223');
    }

    /**
     * @expectedException \Biz\User\UserException
     * @expectedExceptionMessage exception.user.mobile_existed
     */
    public function testChangeMobileWithMobileExisted()
    {
        $this->mockBiz('User:UserDao', [
            [
                'functionName' => 'getByVerifiedMobile',
                'returnValue' => [
                    'id' => 2,
                ],
            ],
            [
                'functionName' => 'get',
                'returnValue' => [
                    'id' => 1,
                ],
            ],
        ]);

        $this->getUserService()->changeMobile(1, '12312312312');
    }

    public function testVerifyInSaltOut()
    {
        $in = 'test';
        $out = 'xw4L6lqFZ9b43YFhZKn73sOgZpK52o/GE60emMO4AUo=';
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $result = $this->getUserService()->verifyInSaltOut($in, $salt, $out);
        $this->assertFalse($result);
    }

    public function testVerifyPasswordOne()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->assertFalse($this->getUserService()->verifyPassword($registeredUser['id'], 'password'));
        $this->assertTrue($this->getUserService()->verifyPassword($registeredUser['id'], 'test_password'));
    }

    public function testParseRegistration()
    {
        $auth['register_mode'] = 'email_or_mobile';
        $this->getSettingService()->set('auth', $auth);
        $registration['emailOrMobile'] = '627099747@qq.com';
        $result = $this->getUserService()->parseRegistration($registration);
        $this->assertEquals('627099747@qq.com', $result['emailOrMobile']);
    }

    public function testParseRegistrationTwice()
    {
        $auth['register_mode'] = 'email_or_mobile';
        $this->getSettingService()->set('auth', $auth);
        $registration['emailOrMobile'] = '13777777976';
        $result = $this->getUserService()->parseRegistration($registration);
        $this->assertEquals('13777777976', $result['mobile']);
    }

    public function testParseRegistrationWithRegisterModeNull()
    {
        $this->mockBiz('System:Setting', [
            [
                'functionName' => 'get',
                'returnValue' => [],
            ],
        ]);

        $registration = [
            'type' => 'email',
            'email' => 'test@test.com',
        ];

        $result = $this->getUserService()->parseRegistration($registration);
        $this->assertEquals($registration, $result);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testParseRegistrationThird()
    {
        $auth['register_mode'] = 'email_or_mobile';
        $this->getSettingService()->set('auth', $auth);
        $registration['emailOrMobile'] = '';
        $this->getUserService()->parseRegistration($registration);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testParseRegistrationForth()
    {
        $auth['register_mode'] = 'email_or_mobile';
        $this->getSettingService()->set('auth', $auth);
        $registration['emailOrMobile'] = 'x';
        $this->getUserService()->parseRegistration($registration);
    }

    public function testParseRegistrationFifth()
    {
        $auth['register_mode'] = 'mobile';
        $this->getSettingService()->set('auth', $auth);
        $registration['mobile'] = '13777822976';
        $result = $this->getUserService()->parseRegistration($registration);
        $this->assertEquals('13777822976', $result['mobile']);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testParseRegistrationSixth()
    {
        $auth['register_mode'] = 'mobile';
        $this->getSettingService()->set('auth', $auth);
        $registration['mobile'] = 'z';
        $this->getUserService()->parseRegistration($registration);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testParseRegistrationSeventh()
    {
        $auth['register_mode'] = 'mobile';
        $this->getSettingService()->set('auth', $auth);
        $registration['mobile'] = 'x';
        $this->getUserService()->parseRegistration($registration);
    }

    public function testParseRegistrationEighth()
    {
        $auth['register_mode'] = '';
        $this->getSettingService()->set('auth', $auth);
        $registration = null;
        $parsedRegistration = $this->getUserService()->parseRegistration($registration);
        $this->assertEquals('web_email', $parsedRegistration['type']);
    }

    public function testIsMobileRegisterMode()
    {
        $auth['register_mode'] = 'mobile';
        $this->getSettingService()->set('auth', $auth);
        $result = $this->getUserService()->IsMobileRegisterMode();
        $this->assertTrue($result);
    }

    public function testGenerateNickname()
    {
        $userInfo = [
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $nickname = $this->getUserService()->generateNickname($userInfo);
        $this->assertNotNull($nickname);
    }

    public function testGenerateEmail()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
        ];
        $email = $this->getUserService()->generateEmail($userInfo);
        $this->assertNotNull($email);
    }

    /**
     * @expectedException \Biz\Common\CommonException
     */
    public function testChangePasswordWithEmptyPassword()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changePassword($registeredUser['id'], '');
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testChangePasswordWithNotExistUserId()
    {
        $this->getUserService()->changePassword(0, 'new_password');
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testVerifyPasswordTwice()
    {
        $this->getUserService()->verifyPassword(0, 'password');
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testVerifyPasswordWithNotExistUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->verifyPassword(0, 'password');
    }

    /**
     *  error.
     */
    public function testFilterFollowingIds()
    {
        $fromUser = $this->createFromUser();
        $toUser = $this->createToUser();
        $followed = $this->getUserService()->follow($fromUser['id'], $toUser['id']);
        $followingIds = $this->getUserService()->filterFollowingIds($fromUser['id'], [999, $toUser['id'], 777]);
        $this->assertContains($toUser['id'], $followingIds);
    }

    public function testFollowOnce() //touser 是被关注者
    {
        $fromUser = $this->createFromUser();
        $toUser = $this->createToUser();
        $followed = $this->getUserService()->follow($fromUser['id'], $toUser['id']);
        $this->assertEquals($fromUser['id'], $followed['fromId']);
        $this->assertEquals($toUser['id'], $followed['toId']);
    }

    public function testSearchUserFollowing()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->searchUserFollowings($user1['id'], 0, 20);
        $this->assertEquals(1, count($result));
    }

    public function testFindUserFollowers()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->findUserFollowers($user2['id'], 0, 20);
        $this->assertEquals(1, count($result));
    }

    public function testFindUserFollowerCount()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->findUserFollowerCount($user2['id']);
        $this->assertEquals(1, $result);
    }

    public function testFindUserFollowing()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->findUserFollowing($user1['id'], 0, 20);
        $this->assertEquals(2, count($result));
    }

    public function testFindAllUserFollowing()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->findAllUserFollowing($user1['id']);
        $this->assertEquals(2, count($result));
    }

    public function testFindUserFollowingCount()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->findUserFollowingCount($user1['id']);
        $this->assertEquals(2, $result);
    }

    public function testFindUserFollowings()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->findUserFollowings($user1['id']);
        $this->assertEquals(2, count($result));
    }

    public function testSearchUserFollowings()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->searchUserFollowings($user1['id'], 0, 20);
        $this->assertEquals(2, count($result));
    }

    public function testSearchUserFollowingCount()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $result = $this->getUserService()->searchUserFollowings($user1['id'], 0, 20);
        $this->assertEquals(2, count($result));
    }

    public function testSearchUserFollowers()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user2['id'], $user3['id']);
        $result = $this->getUserService()->searchUserFollowers($user3['id'], 0, 20);
        $this->assertEquals(2, count($result));
    }

    public function testFindAllUserFollower()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user2['id'], $user3['id']);
        $result = $this->getUserService()->findAllUserFollower($user3['id']);
        $this->assertEquals(2, count($result));
    }

    public function testCountUserFollowers()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user3['id'], $user1['id']);
        $result = $this->getUserService()->countUserFollowers($user1['id'], 0, 20);
        $this->assertEquals(1, $result);
    }

    /**
     * @expectedException \Biz\User\UserException
     * @expectedExceptionMessage exception.user.not_found
     */
    public function testChangeAvatarWithUserNotFoundException()
    {
        $this->mockBiz('User:UserDao', [
            [
                'functionName' => 'get',
                'returnValue' => null,
            ],
            [
                'functionName' => 'getByUUID',
                'returnValue' => null,
            ],
        ]);

        $this->getUserService()->changeAvatar(222, []);
    }

    /**
     * @expectedException \Biz\Content\FileException
     * @expectedExceptionMessage exception.file.not_found
     */
    public function testChangeAvatarByFileIdWithFileNotFoundException()
    {
        $this->getUserService()->changeAvatarByFileId($this->getCurrentUser()->getId(), '');
    }

    /**
     * @expectedException \Biz\Content\FileException
     * @expectedExceptionMessage exception.file.not_found
     */
    public function testChangeAvatarByFileIdWirhRecordEmpty()
    {
        list($naturalSize, $scaledSize) = FileToolkit::getImgInfo(__DIR__.'/../Fixtures/test.jpg', 270, 270);
        $this->mockBiz('Content:FileService', [
            [
                'functionName' => 'getImgFileMetaInfo',
                'returnValue' => [
                    'test',
                    $naturalSize,
                    $scaledSize,
                ],
            ],
            [
                'functionName' => 'getFile',
                'returnValue' => [],
            ],
        ]);

        $this->getUserService()->changeAvatarByFileId($this->getCurrentUser()->getId(), 1);
    }

    public function testChangeAvatarByFileId()
    {
        $filePath = __DIR__.'/../Fixtures/test.jpg';
        list($naturalSize, $scaledSize) = FileToolkit::getImgInfo($filePath, 270, 270);
        $this->mockBiz('Content:FileService', [
            [
                'functionName' => 'getImgFileMetaInfo',
                'returnValue' => [
                    'test',
                    $naturalSize,
                    $scaledSize,
                ],
            ],
            [
                'functionName' => 'getFile',
                'returnValue' => [
                    'id' => 1,
                    'uri' => 'ade',
                ],
            ],
            [
                'functionName' => 'parseFileUri',
                'returnValue' => [
                    'fullpath' => $filePath,
                ],
            ],
            [
                'functionName' => 'deleteFileByUri',
            ],
            [
                'functionName' => 'uploadFile',
                'returnValue' => [
                    'id' => 1,
                ],
            ],
            [
                'functionName' => 'getFilesByIds',
                'returnValue' => [
                    [
                        'id' => 1,
                        'uri' => $filePath,
                    ],
                ],
            ],
            [
                'functionName' => 'findFilesByUris',
                'returnValue' => [],
            ],
        ]);

        $user = $this->createUser('user');
        $result = $this->getUserService()->changeAvatarByFileId($user['id'], 1);

        $this->assertEmpty($user['smallAvatar']);
        $this->assertNotEmpty($result['smallAvatar']);
    }

    public function testChangeAvatarFromImgUrl()
    {
        $user1 = $this->createUser('user1');
        $result = $this->getUserService()->changeAvatarFromImgUrl($user1['id'], __DIR__.'/../Fixtures/test.jpg', [
            'mock' => true,
        ]);
        $this->assertEquals($user1['id'], $result['id']);
    }

    public function testChangeAvatarFromImgUrlWithDeleteOriginFile()
    {
        $user1 = $this->createUser('user1');
        $result = $this->getUserService()->changeAvatarFromImgUrl($user1['id'], __DIR__.'/../Fixtures/test.jpg', [
            'mock' => true,
            'deleteOriginFile' => 0,
        ]);
        $this->assertEquals($user1['id'], $result['id']);
    }

    public function testFindFriendCount()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $this->getUserService()->follow($user2['id'], $user1['id']);

        $count = $this->getUserService()->findFriendCount($user1['id']);
        $this->assertEquals(1, $count);
    }

    public function testFindUnLockedUsersByUserIds()
    {
        $this->getUserDao()->create([
            'id' => 100,
            'nickname' => '1@edusoho.com',
            'password' => '123456',
            'salt' => base_convert(sha1(uniqid(mt_rand(), true)), 16, 36),
            'type' => 'default',
            'email' => '1@edusoho.com',
            'roles' => ['ROLE_USER'],
            'uuid' => 1,
            'locked' => 1,
        ]);

        $this->getUserDao()->create([
            'id' => 101,
            'nickname' => '2@edusoho.com',
            'password' => '123456',
            'salt' => base_convert(sha1(uniqid(mt_rand(), true)), 16, 36),
            'type' => 'default',
            'email' => '2@edusoho.com',
            'roles' => ['ROLE_USER'],
            'uuid' => 2,
            'locked' => 0,
        ]);

        $this->getUserDao()->create([
            'id' => 102,
            'nickname' => '3@edusoho.com',
            'password' => '123456',
            'salt' => base_convert(sha1(uniqid(mt_rand(), true)), 16, 36),
            'type' => 'default',
            'email' => '3@edusoho.com',
            'roles' => ['ROLE_USER'],
            'uuid' => 3,
            'locked' => 0,
        ]);

        $users = $this->getUserService()->findUnLockedUsersByUserIds([100, 101, 102]);
        $this->assertEquals(count($users), 2);
    }

    public function testGetSimpleUser()
    {
        $user1 = $this->createUser('user1');
        $this->getUserService()->changeAvatarFromImgUrl($user1['id'], __DIR__.'/../Fixtures/test.jpg', [
            'mock' => true,
        ]);

        $simpleUser = $this->getUserService()->getSimpleUser($user1['id']);

        $this->assertEquals($user1['id'], $simpleUser['id']);
    }

    public function testCountUsersByLessThanCreatedTime()
    {
        $this->createUser('user1');

        $count = $this->getUserService()->countUsersByLessThanCreatedTime(time() + 1000);
        //2 初始化用户 + 新建用户
        $this->assertEquals(2, $count);
    }

    public function testCountUsersByMobileNotEmpty()
    {
        $user1 = $this->createUser('user1');
        $this->getUserService()->changeMobile($user1['id'], '13399893398');
        $count = $this->getUserService()->countUsersByMobileNotEmpty();

        $this->assertEquals(1, $count);
    }

    public function testFindUsersHasMobile()
    {
        $user1 = $this->createUser('user1');
        $this->getUserService()->changeMobile($user1['id'], '13399893398');
        $user2 = $this->createUser('user2');
        $this->getUserService()->updateUserProfile($user2['id'], ['mobile' => '12233322112']);

        $results = $this->getUserService()->findUsersHasMobile(0, 10, false);

        $this->assertCount(2, $results);

        $results = $this->getUserService()->findUsersHasMobile(0, 10, true);
        $this->assertCount(1, $results);
    }

    public function testCountUsersHasMobile()
    {
        $user1 = $this->createUser('user1');
        $this->getUserService()->changeMobile($user1['id'], '13399893398');
        $user2 = $this->createUser('user2');
        $this->getUserService()->updateUserProfile($user2['id'], ['mobile' => '12233322112']);

        $count = $this->getUserService()->countUserHasMobile(false);

        $this->assertEquals(2, $count);

        $count = $this->getUserService()->countUserHasMobile(true);
        $this->assertEquals(1, $count);
    }

    public function testSearchApprovals()
    {
        $user1 = $this->createUser('user1');
        $this->createApproval($user1['id']);
        $results = $this->getUserService()->searchApprovals(['userId' => $user1['id']], [], 0, 10);
        $this->assertEquals(1, count($results));
    }

    public function testChangeUserOrgWithUserEmpty()
    {
        $this->mockBiz('User:UserDao', [
            [
                'functionName' => 'get',
                'returnValue' => null,
            ],
        ]);

        $result = $this->getUserService()->changeUserOrg(1, '1.');

        $this->assertEmpty($result);
    }

    public function testChangeUserOrgWithOrgCodeEmpty()
    {
        $user1 = $this->createUser('user1');

        $result = $this->getUserService()->changeUserOrg($user1['id'], '');
        $this->assertEquals('1.', $result['orgCode']);
        $this->assertEquals(1, $result['orgId']);
    }

    /**
     * @expectedException \Biz\Org\OrgException
     * @expectedExceptionMessage exception.org.not_found
     */
    public function testChangeUserOrgException()
    {
        $this->mockBiz('Org:OrgService', [
            [
                'functionName' => 'getOrgByOrgCode',
                'returnValue' => [],
            ],
        ]);

        $user = $this->createUser('testUser');
        $this->getUserService()->changeUserOrg($user['id'], 'test');
    }

    public function testChangeUserOrg()
    {
        $this->mockBiz(
            'Org:OrgService',
            [
                [
                    'functionName' => 'getOrgByOrgCode',
                    'returnValue' => [
                        'id' => 1,
                        'orgCode' => 'test1',
                    ],
                ],
            ]
        );

        $user1 = $this->createUser('user1');

        $result = $this->getUserService()->changeUserOrg($user1['id'], 'test1');
        $this->assertEquals('test1', $result['orgCode']);
    }

    public function testBatchUpdateOrg()
    {
        $this->mockBiz(
            'Org:OrgService',
            [
                [
                    'functionName' => 'getOrgByOrgCode',
                    'returnValue' => [
                        'id' => 1,
                        'orgCode' => 'test1',
                    ],
                ],
            ]
        );

        $this->getSettingService()->set('magic', [
            'enable_org' => 1,
        ]);

        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $this->getUserService()->batchUpdateOrg($user1['id'], 'test1');
        $users = $this->getUserService()->findUsersByIds([$user1['id'], $user2['id']]);
        $this->assertEquals(['test1', '1.'], ArrayToolkit::column($users, 'orgCode'));

        $this->getUserService()->batchUpdateOrg([$user1['id'], $user2['id']], 'test1');

        $users = $this->getUserService()->findUsersByIds([$user1['id'], $user2['id']]);

        $code = ArrayToolkit::column($users, 'orgCode');
        $this->assertEquals(['test1', 'test1'], $code);
    }

    public function testUpdateUserUpdatedTime()
    {
        $user1 = $this->createUser('user1');
        $update = $this->getUserService()->updateUserUpdatedTime($user1['id']);
        $this->assertNotEmpty($update);
    }

    public function testVerifyPayPassword()
    {
        $user1 = $this->createUser('user1');
        $result = $this->getUserService()->verifyPayPassword($user1['id'], 'user');
        $this->assertFalse($result);

        $this->getUserService()->changePayPassword($user1['id'], 'user');
        $result = $this->getUserService()->verifyPayPassword($user1['id'], 'user');
        $this->assertTrue($result);
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testVerifyPayPasswordWithNonExistUser()
    {
        $user1 = $this->createUser('user1');
        $this->getUserService()->verifyPayPassword($user1['id'] + 10, 'user');
    }

    /**
     * @expectedException \Biz\User\UserException
     */
    public function testValidateNickname()
    {
        ReflectionUtils::invokeMethod($this->getUserService(), 'validateNickname', [
            '#$%^&*()(',
        ]);
    }

    public function testInitSystemUsers()
    {
        $before = $this->getUserService()->getUserByType('system');
        $this->getUserService()->initSystemUsers();
        $after = $this->getUserService()->getUserByType('system');
        $this->assertNull($before);
        $this->assertNotEmpty($after);

        $this->getUserService()->initSystemUsers();
        $after2 = $this->getUserService()->getUser($after['id'] + 1);
        $this->assertEmpty($after2);
    }

    public function testGetUserByType()
    {
        $this->createUser('user1');
        $result = $this->getUserService()->getUserByType('default');
        $this->assertEquals('default', $result['type']);
    }

    public function testImportUpdateEmail()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user1['password'] = '123456';
        $user2['password'] = '123456';
        $this->getUserService()->importUpdateEmail([
            $user1,
            $user2,
        ]);
    }

    public function testCreateInviteCode()
    {
        $user1 = $this->createUser('user1');
        $result = $this->getUserService()->createInviteCode($user1['id']);

        $this->assertNotEmpty($result['inviteCode']);
    }

    public function testGetUserByInviteCode()
    {
        $user1 = $this->createUser('user1');
        $result = $this->getUserService()->createInviteCode($user1['id']);

        $user = $this->getUserService()->getUserByInviteCode($result['inviteCode']);

        $this->assertEquals($result, $user);
    }

    public function testFindUserIdsByInviteCode()
    {
        $user1 = $this->createUser('user1');
        $result = $this->getUserService()->createInviteCode($user1['id']);
        $this->mockBiz(
            'User:InviteRecordService',
            [
                [
                    'functionName' => 'findRecordsByInviteUserId',
                    'withParams' => [$user1['id']],
                    'returnValue' => [
                        ['id' => 1, 'invitedUserId' => 100],
                    ],
                ],
            ]
        );

        $ids = $this->getUserService()->findUserIdsByInviteCode($result['inviteCode']);

        $this->assertEquals([100], $ids);
    }

    public function testUpdateUserLocale()
    {
        $user1 = $this->createUser('user1');
        $this->getUserService()->updateUserLocale($user1['id'], 'testLocale');
        $result = $this->getUserService()->getUser($user1['id']);
        $this->assertEquals('testLocale', $result['locale']);
    }

    public function testPassApproval()
    {
        $user1 = $this->createUser('user1');
        $this->createApproval($user1['id'], [
            'truename' => 'test',
            'idcard' => '371481199402154559',
        ]);

        $result = $this->getUserService()->passApproval($user1['id']);
        $this->assertTrue($result);
    }

    public function testFindFriends()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $this->getUserService()->follow($user2['id'], $user1['id']);

        $result = $this->getUserService()->findFriends($user1['id'], 0, 10);
        $this->assertEquals(1, count($result));
    }

    public function testCountFriends()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $this->getUserService()->follow($user2['id'], $user1['id']);

        $result = $this->getUserService()->countFriends($user1['id']);
        $this->assertEquals(1, $result);
    }

    public function testFollow()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $this->getUserService()->follow($user2['id'], $user3['id']);
        $this->assertTrue($this->getUserService()->isFollowed($user1['id'], $user3['id']));
        $this->assertTrue($this->getUserService()->isFollowed($user2['id'], $user3['id']));
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testFollowTwice()
    {
        $user1 = $this->createUser('user1');
        $user2 = null;
        $this->getUserService()->follow($user1['id'], $user2['id']);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testFollowThird()
    {
        $user1 = $this->createUser('user1');
        $this->getUserService()->follow($user1['id'], $user1['id']);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testFollowForth()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->follow($user1['id'], $user2['id']);
        $this->getUserService()->follow($user1['id'], $user2['id']);
    }

    public function testhasAdminRoles()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $result = $this->getUserService()->HasAdminRoles($registeredUser['id']);
        $this->assertFalse($result);
        $this->getUserService()->changeUserRoles($registeredUser['id'], [
            'ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_TEACHER',
        ]);
        $result = $this->getUserService()->HasAdminRoles($registeredUser['id']);
        $this->assertTrue($result);
    }

    /**
     *  follow.
     */
    public function testUnFollow()
    {
        $fromUser = $this->createFromUser();
        $toUser = $this->createToUser();
        $this->getUserService()->follow($fromUser['id'], $toUser['id']);
        $result = $this->getUserService()->unFollow($fromUser['id'], $toUser['id']);
        $this->assertEquals(1, $result);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testUnFollowTwcie()
    {
        $user1 = $this->createUser('user1');
        $user2 = null;
        $this->getUserService()->unFollow($user1['id'], $user2['id']);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testUnFollowThird()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $this->getUserService()->unFollow($user1['id'], $user2['id']);
    }

    /**
     *  follow.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testUnFollowNotExistUser()
    {
        $fromUser = $this->createFromUser();
        $toUser = $this->createToUser();
        $this->getUserService()->unFollow($fromUser['id'], 0);
    }

    /**
     *  follow.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testUnFollowWithoutFollowed()
    {
        $fromUser = $this->createFromUser();
        $toUser = $this->createToUser();
        $this->getUserService()->unFollow($fromUser['id'], $toUser['id']);
    }

    /**
     *   follow.
     */
    public function testIsFollowed()
    {
        $fromUser = $this->createFromUser();
        $toUser = $this->createToUser();
        $this->assertFalse($this->getUserService()->isFollowed($fromUser['id'], $toUser['id']));

        $this->getUserService()->follow($fromUser['id'], $toUser['id']);
        $this->assertTrue($this->getUserService()->isFollowed($fromUser['id'], $toUser['id']));
    }

    /**
     *  follow.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testIsFollowedTwice()
    {
        $user1 = null;
        $toUser = $this->createToUser();
        $this->getUserService()->isFollowed($user1['id'], $toUser['id']);
    }

    /**
     *  follow.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testIsFollowedThird()
    {
        $fromUser = $this->createFromUser();
        $user2 = null;
        $this->getUserService()->isFollowed($fromUser['id'], $user2['id']);
    }

    public function testGetLastestApprovalByUserIdAndStatus()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $result = $this->getUserService()->getLastestApprovalByUserIdAndStatus($registeredUser['id'], 'approving');
        $this->assertFalse($result);
    }

    public function testfindUserApprovalsByUserIds()
    {
        $users = [];
        $result = $this->getUserService()->findUserApprovalsByUserIds($users);
        $this->assertEquals(0, count($result));
    }

    // public function testApplyUserApproval()//*

    // {

    // }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testApplyUserApprovalTwice()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __DIR__.'/../Fixtures/test.gif',
            'original.gif',
            'image/gif',
            filesize(__DIR__.'/../Fixtures/test.gif'),
            null
        );

        $userId = null;
        $approval = null;
        $faceImg = $file;
        $backImg = $file;
        $directory = null;
        $this->getUserService()->applyUserApproval($userId, $approval, $faceImg, $backImg, $directory);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testPassApprovalTwice()
    {
        $user = null;
        $note = null;
        $this->getUserService()->passApproval($user['id'], $note);
    }

    public function testRejectApproval()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $result = $this->getUserService()->rejectApproval($registeredUser['id']);
        $this->assertTrue($result);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testRejectApprovalTwice()
    {
        $user = null;
        $note = null;
        $this->getUserService()->rejectApproval($user['id'], $note);
    }

    // public function testDropFieldData()

    // {

    //     $fieldName = null;

    //     $this->getUserService()->dropFieldData($fie);
    // }

    public function testRememberLoginSessionIdOne()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $sessionId = '123.0.0.1';
        $this->getUserService()->rememberLoginSessionId($registeredUser['id'], $sessionId);
        $result = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertNotNull($result['loginSessionId']);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testRememberLoginSessionIdTwice()
    {
        $user = null;
        $sessionId = '123.0.0.1';
        $this->getUserService()->rememberLoginSessionId($user['id'], $sessionId);
    }

    public function testAnalysisRegisterDataByTime()
    {
        $time1 = time();
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $time2 = time();
        $arrays = $this->getUserService()->analysisRegisterDataByTime($time1, $time2);
        $result = $arrays['0'];
        $this->assertGreaterThanOrEqual('3', $result['count']);
    }

    public function testParseAts()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');
        $text = '看我召唤三只猪!@user1,@user2,@user3,谢谢!';
        $result = $this->getUserService()->parseAts($text);
        $this->assertEquals(3, count($result));
    }

    /**
     *   follow.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testIsFollowWithNotExistToId()
    {
        $fromUser = $this->createFromUser();
        $this->getUserService()->isFollowed($fromUser['id'], 888);
    }

    /**
     *   follow.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testIsFollowWithNotExistFromId()
    {
        $toUser = $this->createToUser();
        $this->getUserService()->isFollowed(888, $toUser['id']);
    }

    /**
     *  profile.
     */
    public function testGetUserProfile()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $userProfile = $this->getUserService()->getUserProfile($registeredUser['id']);

        $this->assertEmpty($userProfile['truename']);
        $this->assertEquals('secret', $userProfile['gender']);
        $this->assertNull($userProfile['birthday']);
        $this->assertEmpty($userProfile['city']);
        $this->assertEmpty($userProfile['mobile']);
        $this->assertEmpty($userProfile['qq']);
        $this->assertEmpty($userProfile['signature']);
        $this->assertEmpty($userProfile['about']);
        $this->assertEmpty($userProfile['company']);
        $this->assertEmpty($userProfile['job']);
    }

    /**
     *  profile.
     */
    public function testUpdateUserProfile()
    {
        $updateProfileInfo = [
            'truename' => 'truename',
            'gender' => 'male',
            'birthday' => '2013-01-01',
            'city' => '10000',
            'mobile' => '13888888888',
            'qq' => '123456',
            'company' => 'company',
            'job' => 'job',
            'signature' => 'signature',
            'about' => 'about',
        ];

        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->updateUserProfile($registeredUser['id'], $updateProfileInfo);
        $userProfile = $this->getUserService()->getUserProfile($registeredUser['id']);

        $this->assertEquals($updateProfileInfo['truename'], $userProfile['truename']);
        $this->assertEquals($updateProfileInfo['gender'], $userProfile['gender']);
        $this->assertEquals($updateProfileInfo['birthday'], $userProfile['birthday']);
        $this->assertEquals($updateProfileInfo['city'], $userProfile['city']);
        $this->assertEquals($updateProfileInfo['mobile'], $userProfile['mobile']);
        $this->assertEquals($updateProfileInfo['qq'], $userProfile['qq']);
        $this->assertEquals($updateProfileInfo['signature'], $userProfile['signature']);
        $this->assertEquals($updateProfileInfo['about'], $userProfile['about']);
        $this->assertEquals($updateProfileInfo['job'], $userProfile['job']);
        $this->assertEquals($updateProfileInfo['company'], $userProfile['company']);
    }

    /**
     *  profile.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testUpdateUserProfileWithNotExistUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->updateUserProfile(999, ['gender' => 'male']);
    }

    /**
     *  profile.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testUpdateUserProfileWithErrorGender()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->updateUserProfile($registeredUser['id'], ['gender' => 'xxx']);
    }

    /**
     *  profile.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testUpdateUserProfileWithErrorBirthday()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->updateUserProfile($registeredUser['id'], ['birthday' => 'xxx']);
    }

    /**
     *  profile.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testUpdateUserProfileWithErrorMobile()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->updateUserProfile($registeredUser['id'], ['mobile' => '8888']);
    }

    /**
     *  profile.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testUpdateUserProfileWithErrorQQ()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->updateUserProfile($registeredUser['id'], ['qq' => '1']);
    }

    /**
     *  roles.
     */
    public function testChangeUserRoles()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);

        $this->getUserService()->changeUserRoles($registeredUser['id'], [
                'ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_TEACHER',
            ]
        );
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_TEACHER'], $foundUser['roles']);

        $this->getUserService()->changeUserRoles($registeredUser['id'], [
                'ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN',
            ]
        );
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'], $foundUser['roles']);
    }

    /**
     *  roles.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testChangeUserRolesWithEmptyRoles()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeUserRoles($registeredUser['id'], []);
    }

    /**
     *  roles.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testChangeUserRolesWithNotExistUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeUserRoles(999, ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_TEACHER']);
    }

    /**
     *  roles.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testChangeUserRolesWithIllegalRoles()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeUserRoles($registeredUser['id'], ['ROLE_NOTEXIST_USER']);
    }

    /**
     *  token.
     */
    public function testMakeToken()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $passwordRestToken = $this->getUserService()->makeToken('password-reset', $registeredUser['id'], 1371801141, 'password-reset-data');
        $emailVerifyToken = $this->getUserService()->makeToken('email-verify', $registeredUser['id'], 1371801141, 'data');
        $this->assertNotNull($passwordRestToken);
        $this->assertNotNull($emailVerifyToken);
    }

    public function testGetToken()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $passwordRestToken = $this->getUserService()->makeToken('password-reset', $registeredUser['id'], strtotime('+1 day'), 'password-reset-data');
        $foundPasswordResetToken = $this->getUserService()->getToken('password-reset', $passwordRestToken);
        $this->assertEquals($registeredUser['id'], $foundPasswordResetToken['userId']);
        $this->assertEquals('password-reset', $foundPasswordResetToken['type']);
        $this->assertEquals('password-reset-data', $foundPasswordResetToken['data']);
    }

    /**
     *  token.
     */
    public function testGetTokenSuccess()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $passwordRestToken = $this->getUserService()->makeToken('password-reset', $registeredUser['id'], strtotime('+1 day'), 'password-reset-data');
        $foundPasswordResetToken = $this->getUserService()->getToken('password-reset', $passwordRestToken);

        $this->assertEquals($registeredUser['id'], $foundPasswordResetToken['userId']);
        $this->assertEquals('password-reset', $foundPasswordResetToken['type']);
        $this->assertEquals('password-reset-data', $foundPasswordResetToken['data']);
    }

    /**
     *  token.
     */
    public function testGetTokenFailedWithErrorTypeAndErrorToken()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $passwordRestToken = $this->getUserService()->makeToken('password-reset', $registeredUser['id'], 1371801141, 'password-reset-data');

        $foundPasswordResetToken = $this->getUserService()->getToken('password-reset', 'xxxxxxxxx');
        $this->assertNull($foundPasswordResetToken);

        $foundPasswordResetToken = $this->getUserService()->getToken('not_exist_tokenTyoe', $passwordRestToken);
        $this->assertNull($foundPasswordResetToken);
    }

    /**
     *  token.
     */
    public function testGetTokenFailedWithExpiredTimeLessNow()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $passwordRestToken = $this->getUserService()->makeToken('password-reset', $registeredUser['id'], 1000, 'password-reset-data');

        $foundPasswordResetToken = $this->getUserService()->getToken('password-reset', $passwordRestToken);
        $this->assertNull($foundPasswordResetToken);
    }

    public function testSearchTokenCount()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $emailVerifyToken = $this->getUserService()->makeToken('email-verify', $registeredUser['id'], 1371801141, 'data');
        $result = $this->getUserService()->countTokens(['type' => 'email-verify']);
        $this->assertEquals('1', $result);

        $result = $this->getUserService()->searchTokenCount(['type' => 'email-verify']);
        $this->assertEquals('1', $result);
    }

    /**
     *  token.
     */
    public function testDeleteToken()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $passwordRestToken = $this->getUserService()->makeToken('password-reset', $registeredUser['id'], 1000, 'password-reset-data');
        $deleteResult = $this->getUserService()->deleteToken('password-reset', $passwordRestToken);
        $this->assertTrue($deleteResult);
    }

    /**
     *  token.
     */
    public function testDeleteTokenFailed()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $passwordRestToken = $this->getUserService()->makeToken('password-reset', $registeredUser['id'], 1000, 'password-reset-data');
        $deleteResult = $this->getUserService()->deleteToken('error_type', $passwordRestToken);
        $this->assertFalse($deleteResult);

        $deleteResult = $this->getUserService()->deleteToken('password-reset', 'error_token');
        $this->assertFalse($deleteResult);

        $deleteResult = $this->getUserService()->deleteToken('error_type', 'error_token');
        $this->assertFalse($deleteResult);
    }

    /**
     *  lock.
     */
    public function testLockUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->assertEquals(0, $registeredUser['locked']);
        $this->getUserService()->lockUser($registeredUser['id']);
        $registeredUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(1, $registeredUser['locked']);
    }

    /**
     *   lock.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testLockUserTwice()
    {
        $user = null;
        $this->getUserService()->lockUser($user['id']);
    }

    /**
     *  lock.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testLockNotExistUser()
    {
        $this->getUserService()->lockUser(999);
    }

    /**
     *  lock.
     */
    public function testUnLockUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->assertEquals(0, $registeredUser['locked']);
        $this->getUserService()->lockUser($registeredUser['id']);
        $this->getUserService()->unlockUser($registeredUser['id']);
        $registeredUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(0, $registeredUser['locked']);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testUnLockUserTwice()
    {
        $user = null;
        $this->getUserService()->unlockUser($user);
    }

    public function testPromoteUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->promoteUser($registeredUser['id'], 1);
        $registeredUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(1, $registeredUser['promoted']);
        $this->assertGreaterThan(0, $registeredUser['promotedTime']);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testPromoteUserTwice()
    {
        $user = null;
        $this->getUserService()->promoteUser($user, 1);
    }

    public function testCancelPromoteUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->promoteUser($registeredUser['id'], 1);
        $registeredUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(1, $registeredUser['promoted']);
        $this->getUserService()->cancelPromoteUser($registeredUser['id']);
        $registeredUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals(0, $registeredUser['promoted']);
    }

    /**
     * @expectedException  \Biz\User\UserException
     */
    public function testCancelPromoteUserTwice()
    {
        $user = null;
        $this->getUserService()->cancelPromoteUser($user);
    }

    public function testFindLatestPromotedTeacher()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->changeUserRoles($registeredUser['id'], [
            'ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_TEACHER',
        ]);
        $this->getUserService()->promoteUser($registeredUser['id'], 1);
        $result = $this->getUserService()->findLatestPromotedTeacher(0, 20);
        $result = $result['0'];
        $this->assertEquals($registeredUser['id'], $result['id']);
    }

    public function testWaveUserCounter()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->waveUserCounter($registeredUser['id'], 'newNotificationNum', 1);
        $foundUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals('1', $foundUser['newNotificationNum']);
    }

    /**
     * @expectedException \Biz\Common\CommonException
     */
    public function testWaveUserCounterTwice()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->waveUserCounter($registeredUser['id'], 'newMessageNum', 'ss');
    }

    public function testClearUserCounter()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->waveUserCounter($registeredUser['id'], 'newMessageNum', 1);
        $registeredUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals('1', $registeredUser['newMessageNum']);
        $this->getUserService()->clearUserCounter($registeredUser['id'], 'newMessageNum');
        $registeredUser = $this->getUserService()->getUser($registeredUser['id']);
        $this->assertEquals('0', $registeredUser['newMessageNum']);
    }

    /**
     *  lock.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testUnLockNotExistUser()
    {
        $this->getUserService()->unlockUser(999);
    }

    /**
     *  bind.
     */
    public function testBindUser()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);
        $user = $this->getUserService()->getUserBindByToken('token');
        $this->assertEquals($registeredUser['id'], $user['toId']);
    }

    public function testMarkLoginInfo()
    {
        $user = $this->biz['user'];
        $user['currentIp'] = '127.2.1.'.rand(1, 255);
        $this->biz['user'] = $user;
        $this->getUserService()->markLoginInfo();

        $dbUser = $this->getUserService()->getUser($user['id']);
        $this->assertEquals($user['currentIp'], $dbUser['loginIp']);
    }

    public function testMarkLoginFailed()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $ip = '127.0.0.1';
        $result = $this->getUserService()->markLoginFailed($registeredUser['id'], $ip);
        $this->assertNotNull($result);
    }

    public function testRefreshLoginSecurityFields()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $ip = '152.0.1';
        $result = $this->getUserService()->refreshLoginSecurityFields($registeredUser['id'], $ip);
        $this->assertNull($result);
    }

    public function testCheckLoginForbidden()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $ip = '152.0.1';
        $result = $this->getUserService()->checkLoginForbidden($registeredUser['id'], $ip);
        $this->assertEquals('ok', $result['status']);
    }

    /**
     *  bind.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testBindNotExistUser()
    {
        $this->getUserService()->bindUser('qq', 123123123, 999, ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);
    }

    /**
     *  bind.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testBindUserWithTypeNotInWeiboQQRenren()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $foundBind = $this->getUserService()->bindUser('douban', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);
    }

    /**
     *  bind.
     */
    public function testGetUserBind()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);
        $foundBind = $this->getUserService()->getUserBindByTypeAndFromId('qq', 123123123);

        $this->assertEquals('qq', $foundBind['type']);
        $this->assertEquals(123123123, $foundBind['fromId']);
        $this->assertEquals($registeredUser['id'], $foundBind['toId']);
        $this->assertEquals('token', $foundBind['token']);
    }

    /**
     *  bind.
     */
    public function testGetUserBindWithErrorType()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], [
            'token' => 'token', 'expiredTime' => strtotime('+1 day'),
        ]);
        $userBinder = $this->getUserService()->getUserBindByTypeAndFromId('douban', 123123123);
        $this->assertEmpty($userBinder);
    }

    /**
     *  bind.
     */
    public function testGetUserBindWithErrorParamaters()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);
        $binderUser = $this->getUserService()->getUserBindByTypeAndFromId('qq', 7777);
        $this->assertNull($binderUser);
        $this->getUserService()->getUserBindByTypeAndFromId('douban', 123123123);
        $this->assertNull($binderUser);
    }

    /**
     *  bind.
     */
    public function testGetUserBindWithExpiredTimeInvalidate()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $userInfo = $this->getUserService()->getUserBindByTypeAndFromId('qq', 123123123);

        $this->assertEquals('qq', $userInfo['type']);
        $this->assertEquals('token', $userInfo['token']);
    }

    /**
     *  bind.
     */
    public function testGetUserBindByTypeAndUserId()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $foundBind = $this->getUserService()->getUserBindByTypeAndUserId('qq', $registeredUser['id']);
        $this->assertEquals('qq', $foundBind['type']);
        $this->assertEquals(123123123, $foundBind['fromId']);
        $this->assertEquals($registeredUser['id'], $foundBind['toId']);
        $this->assertEquals('token', $foundBind['token']);
    }

    /**
     *  bind.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testGetUserBindByTypeAndUserIdTwice()
    {
        $registeredUser = null;
        $foundBind = $this->getUserService()->getUserBindByTypeAndUserId('qq', $registeredUser['id']);
    }

    /**
     *  bind.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testGetUserBindByTypeAndUserIdThird()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $type = null;
        $foundBind = $this->getUserService()->getUserBindByTypeAndUserId($type, $registeredUser['id']);
    }

    /**
     *  bind.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testGetUserBindWithInvalidateUserId()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $this->getUserService()->getUserBindByTypeAndUserId('qq', 999);
    }

    /**
     *  bind.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testGetUserBindByTypeAndUserIdWithTypeNotInWeiboQQRenren()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $this->getUserService()->getUserBindByTypeAndUserId('douban', $registeredUser['id']);
    }

    /**
     *  bind.
     */
    public function testFindBindsByUserIdOne()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 111111, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $this->getUserService()->bindUser('weibo', 333333, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $userBinds = $this->getUserService()->findBindsByUserId($registeredUser['id']);
        $fromIds = [];

        foreach ($userBinds as $userBind) {
            array_push($fromIds, $userBind['fromId']);
        }

        $this->assertContains(111111, $fromIds);
        $this->assertContains(333333, $fromIds);
    }

    /**
     *  bind.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testFindBindsByUserIdTwice()
    {
        $user = null;
        $this->getUserService()->findBindsByUserId($user['id']);
    }

    /**
     *  bind.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testFindBindsByErrorUserId()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 111111, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $this->getUserService()->bindUser('weibo', 333333, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $binders = $this->getUserService()->findBindsByUserId(999);

        $this->assertEquals(2, count($binders));
    }

    /**
     * @group tmp
     */
    public function testUnBindUserByTypeAndToIdOne()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 111111, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);

        $result = $this->getUserService()->getUserBindByTypeAndUserId('qq', $registeredUser['id']);
        $this->assertNotNull($result);
        $this->getUserService()->unBindUserByTypeAndToId('qq', $registeredUser['id']);
        $result = $this->getUserService()->getUserBindByTypeAndUserId('qq', $registeredUser['id']);
        $this->assertEmpty($result);
    }

    /**
     *  bind.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testUnBindUserByTypeAndToIdTwice()
    {
        $type = null;
        $user = null;
        $this->getUserService()->unBindUserByTypeAndToId($type, $user['id']);
    }

    /**
     *  bind.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testUnBindUserByTypeAndToIdThird()
    {
        $type = null;
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->unBindUserByTypeAndToId($type, $registeredUser['id']);
    }

    public function testGetUserBindByTypeAndFromId()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 123123123, $registeredUser['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);
        $foundBind = $this->getUserService()->getUserBindByTypeAndFromId('qq', 123123123);
        $this->assertEquals('qq', $foundBind['type']);
    }

    //用户银行校验
    public function testCreateUserPayAgreement()
    {
        $field = ['userId' => 1, 'type' => 0, 'bankName' => '农业银行', 'bankNumber' => 1124, 'bankAuth' => '0eeeee', 'bankId' => 1];
        $bank = $this->getUserService()->createUserPayAgreement($field);
        $this->assertEquals('农业银行', $bank['bankName']);
    }

    public function testGetUserPayAgreement()
    {
        $field = ['userId' => 1, 'type' => 0, 'bankName' => '农业银行', 'bankNumber' => 1124, 'bankAuth' => '0eeeee', 'bankId' => 1];
        $bank = $this->getUserService()->createUserPayAgreement($field);
        $authBank = $this->getUserService()->getUserPayAgreement($bank['id']);
        $this->assertEquals('农业银行', $authBank['bankName']);
    }

    public function testGetUserPayAgreementByUserIdAndBankAuth()
    {
        $field = ['userId' => 1, 'type' => 0, 'bankName' => '农业银行', 'bankNumber' => 1124, 'bankAuth' => '0eeeee', 'bankId' => 1];
        $bank = $this->getUserService()->createUserPayAgreement($field);
        $authBank = $this->getUserService()->getUserPayAgreementByUserIdAndBankAuth(1, '0eeeee');
        $this->assertEquals('农业银行', $authBank['bankName']);
    }

    public function testGetUserPayAgreementByUserId()
    {
        $field = ['userId' => 1, 'type' => 0, 'bankName' => '农业银行', 'bankNumber' => 1124, 'bankAuth' => '0eeeee', 'bankId' => 1];
        $bank = $this->getUserService()->createUserPayAgreement($field);
        $authBank = $this->getUserService()->getUserPayAgreementByUserId(1);
        $this->assertEquals('农业银行', $authBank['bankName']);
    }

    public function testUpdateUserPayAgreementByUserIdAndBankAuth()
    {
        $field = ['userId' => 1, 'type' => 0, 'bankName' => '农业银行', 'bankNumber' => 1124, 'bankAuth' => '0eeeee', 'bankId' => 1];
        $bank = $this->getUserService()->createUserPayAgreement($field);
        $authBank = $this->getUserService()->updateUserPayAgreementByUserIdAndBankAuth(1, '0eeeee', ['bankName' => '招商银行']);
        $this->assertEquals(1, 1);
    }

    public function testFindUserPayAgreementsByUserId()
    {
        $field = ['userId' => 1, 'type' => 0, 'bankName' => '农业银行', 'bankNumber' => 1124, 'bankAuth' => '0eeeee', 'bankId' => 1];
        $bank = $this->getUserService()->createUserPayAgreement($field);
        $authBank = $this->getUserService()->findUserPayAgreementsByUserId(1);
        $this->assertEquals('农业银行', $authBank[0]['bankName']);
    }

    public function testDeleteUserPayAgreements()
    {
        $field = ['userId' => 1, 'type' => 0, 'bankName' => '农业银行', 'bankNumber' => 1124, 'bankAuth' => '0eeeee', 'bankId' => 1];
        $bank = $this->getUserService()->createUserPayAgreement($field);
        $userPayAgreements = $this->getUserService()->deleteUserPayAgreements(1);
        $this->assertEquals(1, $userPayAgreements);
    }

    /**
     *  bind.
     *
     * @expectedException  \Biz\User\UserException
     */
    public function testUnBindUserByTypeAndToIdWithErrorUserId()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 111111, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $this->getUserService()->unBindUserByTypeAndToId('qq', 999);
    }

    /**
     *  bind.
     *
     * @expectedException \Biz\User\UserException
     */
    public function testUnBindUserByTypeAndToIdWithErrorType()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);
        $this->getUserService()->bindUser('qq', 111111, $registeredUser['id'], ['token' => 'token', 'expiredTime' => 100]);
        $this->getUserService()->unBindUserByTypeAndToId('douban', $registeredUser['id']);
    }

    public function testGenerateNicknamePrefix()
    {
        $user = $this->createUser('adminabc');
        $user['nickname'] = 'admin';
        $nickname = $this->getUserService()->generateNickname($user);
        $this->assertEquals(stripos($nickname, 'admin'), 0);
    }

    public function testGenerateNicknameSpecialChar()
    {
        $this->createUser('abcefg');
        $user['nickname'] = '🐎abcefg✈🐯️';
        $nickname = $this->getUserService()->generateNickname($user);
        $this->assertEquals(stripos($nickname, 'abcefg'), 0);
    }

    public function testGenerateNicknameEmptyRaw()
    {
        $user = [];
        $nickname = $this->getUserService()->generateNickname($user);
        $this->assertEquals(stripos($nickname, 'user'), 0);
    }

    public function testUpdateUserNewMessageNum()
    {
        $currentUser = new CurrentUser();
        $currentUser->fromArray([
            'id' => 2,
            'nickname' => 'admin1',
            'email' => 'admin3@admin.com',
            'password' => 'admin',
            'currentIp' => '127.0.0.1',
            'roles' => ['ROLE_USER', 'ROLE_ADMIN'],
        ]);
        $currentUser->__set('newMessageNum', 2);
        $this->getServiceKernel()->setCurrentUser($currentUser);
        $this->mockBiz(
            'User:UserDao',
            [
                [
                    'functionName' => 'update',
                    'withParams' => [2, ['newMessageNum' => 1]],
                ],
            ]
        );
        $result = $this->getUserService()->updateUserNewMessageNum(2, 1);
        $this->assertNull($result);
    }

    public function testMakeUUID()
    {
        $uuid = $this->getUserService()->makeUUID();

        $this->assertNotNull($uuid);
        $this->assertEquals(40, strlen($uuid));
    }

    public function testGenerateUUID()
    {
        $uuid = $this->getUserService()->generateUUID();

        $this->assertNotNull($uuid);
        $this->assertEquals(40, strlen($uuid));
    }

    public function testGetSmsCaptchaStatusWithLowProtective()
    {
        $settingService = $this->mockBiz(
            'System:SettingService',
            [
                [
                    'functionName' => 'get',
                    'withParams' => ['auth', []],
                    'returnValue' => [
                        'register_mode' => 'mobile',
                        'register_protective' => 'low',
                    ],
                ],
            ]
        );

        $this->assertEquals(
            'captchaIgnored',
            $this->getUserService()->getSmsRegisterCaptchaStatus('128.3.2.1', false)
        );

        $this->assertEquals(
            'captchaIgnored',
            $this->getUserService()->getSmsRegisterCaptchaStatus('128.3.2.1', true)
        );

        $this->assertEquals(
            'captchaRequired',
            $this->getUserService()->getSmsRegisterCaptchaStatus('128.3.2.1', false)
        );

        $settingService->shouldHaveReceived('get')->times(3);
    }

    public function testGetSmsCaptchaStatusWithNoneProtective()
    {
        $settingService = $this->mockBiz(
            'System:SettingService',
            [
                [
                    'functionName' => 'get',
                    'withParams' => ['auth', []],
                    'returnValue' => [
                        'register_mode' => 'mobile',
                        'register_protective' => 'none',
                    ],
                ],
            ]
        );

        $this->assertEquals(
            'captchaIgnored',
            $this->getUserService()->getSmsRegisterCaptchaStatus('128.3.2.1', false)
        );

        $this->assertEquals(
            'captchaIgnored',
            $this->getUserService()->getSmsRegisterCaptchaStatus('128.3.2.1', true)
        );

        $this->assertEquals(
            'captchaIgnored',
            $this->getUserService()->getSmsRegisterCaptchaStatus('128.3.2.1', false)
        );
        $settingService->shouldHaveReceived('get')->times(3);
    }

    public function testGetSmsCaptchaStatusWithNoneMobile()
    {
        $settingService = $this->mockBiz(
            'System:SettingService',
            [
                [
                    'functionName' => 'get',
                    'withParams' => ['auth', []],
                    'returnValue' => [
                        'register_mode' => 'email',
                        'register_protective' => 'high',
                    ],
                ],
            ]
        );

        $this->assertEquals(
            'smsUnsendable',
            $this->getUserService()->updateSmsRegisterCaptchaStatus('128.3.2.1')
        );

        $this->assertEquals(
            'smsUnsendable',
            $this->getUserService()->updateSmsRegisterCaptchaStatus('128.3.2.1')
        );

        $settingService->shouldHaveReceived('get')->times(2);
    }

    public function testUpdateSmsRegistrationCaptchaCode()
    {
        $settingService = $this->mockBiz(
            'System:SettingService',
            [
                [
                    'functionName' => 'get',
                    'withParams' => ['auth', []],
                    'returnValue' => [
                        'register_mode' => 'mobile',
                        'register_protective' => 'high',
                    ],
                ],
            ]
        );

        $this->assertEquals(
            'captchaRequired',
            $this->getUserService()->getSmsRegisterCaptchaStatus('128.3.2.1', false)
        );

        $this->assertEquals(
            'captchaRequired',
            $this->getUserService()->getSmsRegisterCaptchaStatus('128.3.2.1', true)
        );

        $settingService->shouldHaveReceived('get')->times(2);
    }

    public function testInitPassword()
    {
        $userInfo = [
            'nickname' => 'test_nickname',
            'password' => 'test_password',
            'email' => 'test_email@email.com',
        ];
        $registeredUser = $this->getUserService()->register($userInfo);

        $user = $this->getUserService()->initPassword($registeredUser['id'], 'newPassword');

        $this->assertEquals('1', $user['passwordInit']);
    }

    public function testFindUserBindByTypeAndFromIds()
    {
        $user = $this->createUser('user1');
        $this->getUserService()->bindUser('qq', 123123123, $user['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);

        $users = $this->getUserService()->findUserBindByTypeAndFromIds('qq', []);
        $this->assertEmpty($users);

        $users = $this->getUserService()->findUserBindByTypeAndFromIds('qq', [123123123]);
        $this->assertEquals(1, $users[0]['id']);
    }

    public function testFindUserBindByTypeAndToIds()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');

        $this->getUserService()->bindUser('qq', 123123123, $user1['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);
        $this->getUserService()->bindUser('qq', 123123211, $user2['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);

        $users = $this->getUserService()->findUserBindByTypeAndToIds('qq', []);
        $this->assertEmpty($users);

        $users = $this->getUserService()->findUserBindByTypeAndToIds('qq', [$user1['id'], $user2['id']]);
        $this->assertCount(2, $users);
    }

    /**
     * @expectedException \Biz\User\UserException
     * @expectedExceptionMessage exception.user.not_found
     */
    public function testFindUserBindByTypeAndUserIdException()
    {
        $this->getUserService()->findUserBindByTypeAndUserId('qq', 223);
    }

    public function testFindUserBindByTypeAndUserId()
    {
        $user = $this->createUser('user');
        $this->getUserService()->bindUser('qq', 123123123, $user['id'], ['token' => 'token', 'expiredTime' => strtotime('+1 day')]);

        $result = $this->getUserService()->findUserBindByTypeAndUserId('qq', $user['id']);
        $this->assertEquals(1, $result[0]['id']);
    }

    public function testCountUserFollowings()
    {
        $user1 = $this->createUser('user1');
        $user2 = $this->createUser('user2');
        $user3 = $this->createUser('user3');

        $this->getUserService()->follow($user1['id'], $user2['id']);
        $this->getUserService()->follow($user1['id'], $user3['id']);
        $count = $this->getUserService()->countUserFollowings($user1['id']);
        $this->assertEquals(2, $count);
    }

    protected function createUser($user)
    {
        $userInfo = [];
        $userInfo['email'] = "{$user}@{$user}.com";
        $userInfo['nickname'] = "{$user}";
        $userInfo['password'] = "{$user}123";
        $userInfo['loginIp'] = '127.0.0.1';

        return $this->getUserService()->register($userInfo);
    }

    protected function createFromUser()
    {
        $fromUser = [];
        $fromUser['email'] = 'fromUser@fromUser.com';
        $fromUser['nickname'] = 'fromUser';
        $fromUser['password'] = 'fromUser';

        return $this->getUserService()->register($fromUser);
    }

    protected function createToUser()
    {
        $toUser = [];
        $toUser['email'] = 'toUser@toUser.com';
        $toUser['nickname'] = 'toUser';
        $toUser['password'] = 'toUser';

        return $this->getUserService()->register($toUser);
    }

    protected function createApproval($userId, $approval = [])
    {
        $sourceFile = __DIR__.'/../Fixtures/test.gif';
        $test1File = __DIR__.'/../Fixtures/test_test1.gif';
        $test2File = __DIR__.'/../Fixtures/test_test2.gif';

        copy($sourceFile, $test1File);
        copy($sourceFile, $test2File);
        $file1 = new UploadedFile(
            $test1File,
            'original.gif',
            'image/gif',
            filesize($test1File),
            UPLOAD_ERR_OK,
            true
        );
        $file2 = new UploadedFile(
            $test2File,
            'original.gif',
            'image/gif',
            filesize($test2File),
            UPLOAD_ERR_OK,
            true
        );

        $faceImg = $file1;
        $backImg = $file2;
        $directory = $this->getContainer()->getParameter('topxia.upload.private_directory').'/approval';

        return $this->getUserService()->applyUserApproval($userId, $approval, $faceImg, $backImg, $directory);
    }

    private function initFile()
    {
        $groups = $this->getFileService()->getAllFileGroups();

        foreach ($groups as $group) {
            $this->getFileService()->deleteFileGroup($group['id']);
        }

        $this->getFileService()->addFileGroup([
            'name' => '默认文件组',
            'code' => 'default',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '缩略图',
            'code' => 'thumb',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '课程',
            'code' => 'course',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '用户',
            'code' => 'user',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '课程私有文件',
            'code' => 'course_private',
            'public' => 0,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '资讯',
            'code' => 'article',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '临时目录',
            'code' => 'tmp',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '全局设置文件',
            'code' => 'system',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '小组',
            'code' => 'group',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '编辑区',
            'code' => 'block',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '班级',
            'code' => 'classroom',
            'public' => 1,
        ]);
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->createService('User:UserService');
    }

    /**
     * @return UserDao
     */
    protected function getUserDao()
    {
        return $this->createDao('User:UserDao');
    }

    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    protected function getFileService()
    {
        return $this->createService('Content:FileService');
    }

    protected function getPasswordEncoder()
    {
        return new MessageDigestPasswordEncoder('sha256');
    }
}
