<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;
use App\Models\Donation;
use App\Http\Controllers\Controller;

class CampaignController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data campaigns
        $campaigns = Campaign::with('user')->with('sumDonation')->when(request()->q, function($campaigns) {
            $campaigns = $campaigns->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);

        //return with response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Campaigns',
            'data'    => $campaigns,
        ], 200);
    }
    
    /**
     * show
     *
     * @param  mixed $slug
     * @return void
     */
    public function show($slug)
    {
        //get detail data campaign
        $campaign = Campaign::with('user')->with('sumDonation')->where('slug', $slug)->first();

        //get data donation by campaign
        $donations = Donation::with('donatur')->where('campaign_id', $campaign->id)->where('status', 'success')->latest()->get();

        if($campaign) {

            //return with response JSON
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Data Campaign : '. $campaign->title,
                'data'      => $campaign,
                'donations' => $donations
            ], 200);
        }

        //return with response JSON
        return response()->json([
            'success' => false,
            'message' => 'Data Campaign Tidak Ditemukan!',
        ], 404);
    }
}