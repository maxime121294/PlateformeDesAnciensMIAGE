<?php
namespace UserBundle\Security\Core\User;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
class FOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $data = $response->getResponse();
        $serviceName = $response->getResourceOwner()->getName();
        $birthday = null;

        if ($serviceName === 'google'){
            $username = $data['id'];
            $firstname = $data['name']['givenName'];
            $lastname = $data['name']['familyName'];
            $email = $data['emails'][0]['value'];
            if (isset($data['birthday'])) {
                $birthday = $data['birthday'];
            }
        }

        if ($serviceName === 'facebook'){
            $username = $response->getUsername();
            $lastname = $response->getLastName();
            $firstname = $response->getFirstName();
            $email = $response->getEmail();
            if (isset($data['birthday'])) {
                $birthday = $data['birthday'];
            }
        }

        if ($serviceName === 'linkedin'){
            $username = $data['id'];
            $firstname = $data['firstName'];
            $lastname = $data['lastName'];
            $email = $data['emailAddress'];
            if (isset($data['birthday'])) {
                $birthday = $data['birthday'];
            }
        }

        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $user = $this->userManager->findUserByEmail($email);

            $setter = 'set'.ucfirst($serviceName);
            $setterId = $setter.'Id';
            $setterToken = $setter.'AccessToken';
            // create new user here
            if (null === $user) {
                $user = $this->userManager->createUser();
                $user->$setterId($username);
                $user->$setterToken($response->getAccessToken());
                //I have set all requested data with the user's username
                //modify here with relevant data
                $user->setFirstName($firstname);
                $user->setLastname($lastname);
                $user->setEmail($email);
                $user->setPlainPassword($username);
                if (!is_null($birthday)) {
                    $user->setBirthday(new \DateTime($birthday));
                }
                $user->setEnabled(true);
                $this->userManager->updateUser($user);

                return $user;
            }
            else {
                $user->$setterId($username);
                $user->$setterToken($response->getAccessToken());

                $this->userManager->updateUser($user);

                return $user;
            }
        }
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        
        //update access token
        $user->$setter($response->getAccessToken());
        return $user;
    }
}