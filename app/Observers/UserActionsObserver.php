<?php

namespace App\Observers;


use App\Models\UserAction;

class UserActionsObserver
{
    
    // public function retrieved($model)
    // {
       
    //     $action = 'created';
    //     UserAction::create([
    //         'user_id'      => $model->id,
    //         'action'       => $action,
    //         'action_model' => $model->getTable(),
            
    //     ]);

    // }

    public function saved($model)
    {
      
        if ($model->wasRecentlyCreated == true) {
            // Data was just created
            $action = 'created';
        } else {
            // Data was updated
            $action = 'updated';
        }
      
            UserAction::create([
                'user_id'      => $model->id,
                'action'       => $action,
                'action_model' => $model->getTable(),
              
            ]);
        
    }

    public function deleted($model): void
    {
        $action = 'Soft_Deleted';
        
        UserAction::create([
            'user_id'      => $model->id,
            'action'       => $action,
            'action_model' => $model->getTable(),
          
        ]);
    }

    //restored
    public function restored($model)
    {
        $action = 'Restored';
        
        UserAction::create([
            'user_id'      => $model->id,
            'action'       => $action,
            'action_model' => $model->getTable(),
          
        ]);

    }

   
}
