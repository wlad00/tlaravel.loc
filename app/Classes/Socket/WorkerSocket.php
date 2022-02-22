<?php

namespace App\Classes\Socket;


class WorkerSocket{


    public function __construct()
    {

    }

    public function freshVisitor(){




        $this->visitor->update(['fresh'=>1]);

        /* no user */
        if(!$this->visitor->is_user)
                                    return;



        /* 1 user */
        $vis_us_arr = Visitors_users::where('visitor_id',$this->visitor->id)->get();

        /* 2 arrUsers */
        $arrUsers = [];
        foreach($vis_us_arr as $vis_us){

            array_push($arrUsers,User::find($vis_us->user_id));
        }



        /* 3 lastUser */
        $maxUpdate = '0';
        foreach($arrUsers as $user){

//            $user->str_Date = Service::dateToString($user->updated_at);

            $str_date = (string)$user->updated_at;


            if($str_date<$maxUpdate)
                                            continue;

                $maxUpdate = $str_date;

                $lastUser = $user;

        }


        if(!isset($lastUser)) return;




        $mainVisitor = Visitor::where('user_id',$lastUser->id)->first();


        $lastUser->update(['fresh'=>1]);



        if(isset($mainVisitor))
        $mainVisitor->update(['fresh'=>1]);



        $this->visitor->update(['fresh'=>0]);


        $k = 0;
        /*foreach($vis_us_arr as $vis_us){

            $vis_us_arr2 = Visitors_users::where('user_id',$vis_us->user_id)->get();


            foreach($vis_us_arr2 as $vis_us2){

               $vis = Visitor::where('user_id',$vis_us2->user_id);

               $vis->update(['fresh'=>1]);
            }
        }*/
    }

    public function confirmVisitor(){

        if(!$this->visitor || !$this->visitor->undefined || $this->visitor->is_bot == 'Bot')
                                        return;

        $this->visitor->undefined = null;
        $this->visitor->json_php = null;

        $this->visitor->save();
    }

    public function confirmItemVisitor(){

        if(!$this->item_visitor || !$this->item_visitor->undefined ||  $this->item_visitor->is_bot == 'Bot')
                                        return;

        $this->item_visitor->undefined = null;

        $this->item_visitor->save();
    }

    public function saveBrief(){

        if(!$this->visitor->undefined)
                                   return;

        /*if($this->visitor->breif)
                            return;*/

        $this->visitor->brief =  f_WorkerGetAll::getBrief($this->allParams,$this->P_ip);

        $this->visitor->save();

    }

    public function updateDifZone(){


        if($this->visitor->difzone != $this->difzone ||
            $this->visitor->timezone != $this->allParams['timezone'] ){

//1 update visitor
            $this->visitor->difzone = $this->difzone;
            $this->visitor->timezone = $this->difzone;
            $this->visitor->save();

//3 update subscription

            PushSubscription::
                updateDifzone($this->visitor->id,$this->difzone);
        }

        /*-------------------------------------------------*/

          if(!isset($this->item_visitor))
                                      return;

        if( $this->item_visitor->difzone != $this->difzone ||
              $this->item_visitor->timezone != $this->allParams['timezone'] ){

  //1 update visitor
              $this->item_visitor->difzone = $this->difzone;
              $this->item_visitor->timezone = $this->difzone;
              $this->item_visitor->save();

  //3 update subscription

              PushSubscription::
                  updateDifzone($this->item_visitor->id, $this->difzone);
          }


    }


    public function checkUser(){

        if(!$this->user)
                        return;

        //1

        Visitor::addIsUser($this->item_visitor);

        //2

        Visitors_users::addVisUs($this->item_visitor, $this->user->id);
        Visitors_users::addVisUs($this->visitor,$this->user->id);


        //3

        if($this->item_visitor->referer != $this->visitor->referer)
            $this->visitor->update(['referer'=>$this->item_visitor->referer]);

        if($this->item_visitor->referer != $this->user->referer)
            $this->user->update(['referer'=>$this->item_visitor->referer]);

        //3

//        if($this->item_visitor)


    }

    public function checkVisitor(){

        if($this->user)
                    return;

        //1 swap to Old
        $this->visitor = Visitor::swapVisitor
                ($this->visitor,$this->item_visitor);

        //2 add canvas,hash to Visitor
        $this->createCheckingItems();

    }


    public function createCheckingItems(){

        if($this->user)
                        return;


        $this->canvasElem->createItem($this->visitor->id);
        $this->hashElem->createItem($this->visitor->id);
    }

    public function checkUser1(){

//        return;// del
        //1
        if(!$this->user)  return;


        //2
//        if(!$this->user->visitor)
//                        $this->addVisitorToUser();

       /* $vis_us = Visitors_users
            ::where('visitor_id',$this->visitor->id)
            ->where('user_id',$this->user->id)
            ->first();

        if(!$vis_us)
                Visitors_users::create([
                    'visitor_id'=>$this->visitor->id,
                    'user_id'=>$this->user->id
                ]);*/

        //2
        Visitors_users::addVisUs($this->visitor,$this->user->id);
        Visitors_users::addVisUs($this->user->visitor,$this->user->id);

        //3
        $this->visitor = $this->user->visitor;


    }




    public function getVisitorId(){

        if($this->user){



            return $this->user->visitor->id;
        }
        else

        return $this->visitor->id;
    }

    public function getVisitor(){

        return $this->visitor;
    }

  /*  public function getSubscription(){

        return $this->subscription;
    }*/

    public function getTimeNotify(){

        if (!$this->subscription)

                    return CONSTANT::TIME_NOTIFY;



        if($this->subscription->visitor_id != $this->visitor->id)
            $this->subscription->update(['visitor_id'=>$this->visitor->id]);


        return $this->subscription->time_notify;
    }

    public function getArrPhilo(){

        return [
            trans('KlavaHelp.philo1'),
            trans('KlavaHelp.philo2'),
            trans('KlavaHelp.philo3'),
            trans('KlavaHelp.philo4')
        ];
    }

    public function getArrHelp(){
        return [
            trans('KlavaHelp.help1'),
            trans('KlavaHelp.help2'),
            trans('KlavaHelp.help3'),
            trans('KlavaHelp.help4')

        ];
    }


    public function getArrNotify(){



        if (!$this->subscription ){

                    return CONSTANT::ARR_NOTIFY;
        }



        if($this->subscription->visitor_id != $this->visitor->id)

            $this->subscription->update(['visitor_id'=>$this->visitor->id]);


        return $this->subscription->arr_notify;
    }

    public function addInfoAdmin(){


        $InfoA = $this->getInfoAdmin();


        $this->addNumberBuild($InfoA);

    }


    /*----- private 1 ------------------*/

    private function getInfoAdmin(){

        $info_admin = $this->visitor->info_admin;

        if(!isset($info_admin))
                        $info_admin = '{}';

        return json_decode($info_admin);
    }

    private function addNumberBuild($InfoA){

        if(!isset($InfoA->number_build))
                        $InfoA->number_build = 0;

        $InfoA->number_build ++;

        $this->visitor->update(['info_admin'=>json_encode($InfoA)]);
    }

    private function addVisitorToUser(){


        Storage::disk('admin_disk')->append('error.txt', date('d.m.y  H:i:s').
            '   '.'element'
            .PHP_EOL.PHP_EOL);

        //1
        if($this->visitor->user)
            $this->visitor = Visitor::createVisitor((object)[]);

        //2
        $this->visitor->update([
           'main_visitor'=>$this->main_visitor_id,
           'user_id'=>$this->user->id
        ]);

        //3
        $this->user = User::find($this->user->id);

    }



    /*---- private 1 ---------------------------------*/

    private function getCheckingElements(){

        $this->canvasElem = $this->makeCheckingElement('canvas');
        $this->hashElem = $this->makeCheckingElement('hash');

        $canvas_visitor_id = $this->canvasElem->getVisitorId();

        $hash_visitor_id = $this->hashElem->getVisitorId();

        //2
        $this->item_visitor_id = Visitor::getItemVisitorId
        ($canvas_visitor_id,$hash_visitor_id);

        $this->item_visitor = Visitor::find($this->item_visitor_id);



    }

    /*---- private 2 ------*/

    private function createNewVisitor(){

        //1
        $this->visitor = Visitor::createVisitor((object)[]);

        //2
        $this->visitor->main_visitor = $this->main_visitor_id;

    }

    private function makeCheckingElement($type){

        $hash_value = $this->getHashValue($type);
        $create_arr = $this->getCreateArr($type,$hash_value);

        return new CheckingElement(
            $type,
            $hash_value,
            $create_arr
        );
    }

    /*---- private 3 ------*/

    private function getHashValue($type){

        switch($type){

            case('canvas'): return f_WorkerGetAll::getIpCanvas(
                $this->P_ip['ip'],
                $this->allParams['canvas']
            );
            case('hash'): return Service::getHashFromObj($this->allParams);
        }
    }


    private function getCreateArr($type,$hash_value){


        switch($type){

            case('canvas'): return [
                'visitor'=>$this->visitor->id,
                'ip'=>$this->P_ip['ip'],
                'ip2_canvas'=>$hash_value,
                'country'=>$this->P_ip['country'],
                'city'=>$this->P_ip['city'],
                'ip_proxy'=>$this->P_ip['ip_proxy'],
                'canvas'=>$this->allParams['canvas'],
                'domain'=>$_SERVER['SERVER_NAME']
            ];
            case('hash'): return [
                'visitor'=>$this->visitor->id,
                'hash'=>$hash_value,
                'hash_data'=>json_encode($this->allParams),
                'domain'=>$_SERVER['SERVER_NAME']
            ];
        }

    }






}