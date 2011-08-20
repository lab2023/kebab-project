<?php
 
/**
 * Kebab Project
 *
 * LICENSE
 *
 * This source file is subject to the  Dual Licensing Model that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kebab-project.com/cms/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 *
 * @category   
 * @package    
 * @subpackage 
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
 
/**
 * 
 *
 * @category   
 * @package    
 * @subpackage 
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_Model_Feedback 
{
    public static function getFeedbackByUserId($userId, $options)
    {
        $lang = Zend_Auth::getInstance()->getIdentity()->language;
        $query = Doctrine_Query::create()
                ->select('
                    feedback.*,
                    application.*,
                    applicationTranslate.title as title')
                ->from('Model_Entity_Feedback feedback')
                ->innerJoin('feedback.Application application')
                ->leftJoin('application.Translation applicationTranslate')
                ->where('feedback.user_id = ?', $userId)
                ->andWhere('applicationTranslate.lang = ?', $lang)
                ->useQueryCache(Kebab_Cache_Query::isEnable());

        if (array_key_exists('sort', $options)) {
            $query->orderBy($options['sort']);
        }

        return $query;
    }

    public static function insert($userSessionId, $applicationIdentity, $description)
    {
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $application = Doctrine_Core::getTable('Model_Entity_Application')->findOneByidentity($applicationIdentity);
            $feedback = new Model_Entity_Feedback();
            $feedback->Application = $application;
            $feedback->status = 'open';
            $feedback->description = $description;
            $feedback->user_id = $userSessionId;
            $feedback->save();
            return Doctrine_Manager::connection()->commit() ? $feedback : false;
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }
}
