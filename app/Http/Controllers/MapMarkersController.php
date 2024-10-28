<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateMapMarkersRequest;
use App\Http\Requests\UpdateMapMarkersRequest;
use App\Repositories\MapMarkersRepository;
use Auth;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class MapMarkersController extends AppBaseController
{
    /** @var  MapMarkersRepository */
    private $mapMarkersRepository;

    public function __construct(MapMarkersRepository $mapMarkersRepo)
    {
        $this->mapMarkersRepository = $mapMarkersRepo;
    }

    /**
     * Display a listing of the MapMarkers.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $type = $this->getBasicTypes();

        $mapMarkers = $this->mapMarkersRepository->getMarkersByType($type);

        return view('map_markers.index')
            ->with('mapMarkers', $mapMarkers);
    }

    /**
     * Show the form for creating a new MapMarkers.
     *
     * @return Response
     */
    public function create()
    {
        return view('map_markers.create');
    }

    /**
     * Store a newly created MapMarkers in storage.
     *
     * @param CreateMapMarkersRequest $request
     *
     * @return Response
     */
    public function store(CreateMapMarkersRequest $request)
    {
        $input = $request->all();

        $mapMarkers = $this->mapMarkersRepository->create($input);

        Flash::success('Map Markers saved successfully.');

        return redirect(route('mapMarkers.index'));
    }

    /**
     * Display the specified MapMarkers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mapMarkers = $this->mapMarkersRepository->find($id);

        if (empty($mapMarkers)) {
            Flash::error('Map Markers not found');

            return redirect(route('mapMarkers.index'));
        }

        return view('map_markers.show')->with('mapMarkers', $mapMarkers);
    }

    /**
     * Show the form for editing the specified MapMarkers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mapMarkers = $this->mapMarkersRepository->find($id);

        if (empty($mapMarkers)) {
            Flash::error('Map Markers not found');

            return redirect(route('mapMarkers.index'));
        }

        return view('map_markers.edit')->with('mapMarkers', $mapMarkers);
    }

    /**
     * Update the specified MapMarkers in storage.
     *
     * @param int $id
     * @param UpdateMapMarkersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMapMarkersRequest $request)
    {
        $mapMarkers = $this->mapMarkersRepository->find($id);

        if (empty($mapMarkers)) {
            Flash::error('Map Markers not found');

            return redirect(route('mapMarkers.index'));
        }

        $mapMarkers = $this->mapMarkersRepository->update($request->all(), $id);

        Flash::success('Map Markers updated successfully.');

        return redirect(route('mapMarkers.index'));
    }

    /**
     * Remove the specified MapMarkers from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mapMarkers = $this->mapMarkersRepository->find($id);

        if (empty($mapMarkers)) {
            Flash::error('Map Markers not found');

            return redirect(route('mapMarkers.index'));
        }

        $this->mapMarkersRepository->delete($id);

        Flash::success('Map Markers deleted successfully.');

        return redirect(route('mapMarkers.index'));
    }

    public function getBasicTypes()
    {
        $type = "";
        $panel = "";
        if (Auth::user()->user_type === 'editor_lab') {
            $type = "lab-test";
            $panel = "1";
        } else if (Auth::user()->user_type === 'editor_pharmacy') {
            $type = "medicine";
            $panel = "0";
        } else if (Auth::user()->user_type === 'admin_pharmacy') {
            $type = "medicine";
            $panel = "0";
        }

        $data = array(
            'type' => $type,
            'panel-test' => $panel,
        );

        return $data;
    }

    public function getCoordinates($zip)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($zip) . "&key=AIzaSyCRPRccs93XYIWyD-1I5ygtkzQ_ROCFafU";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);
        // $result1[] = $result['results'][0];
        // $result2[] = $result1[0]['geometry'];
        // $result3[] = $result2[0]['location'];
        return $result;
    }

    public function getCityStateByZipCode(Request $request)
    {
        $zip = $request['zip'];
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $zip . "&sensor=true&key=".env('GOOGLE_APIKEY');
        $json = file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        $country_id = "";
        $state_id = "";
        $city_id = "";
        // return $status;
        if ($status == "OK") {
            $complete_address = $data->results[0]->formatted_address;
            $address = explode(",", $complete_address);
            $countryName = str_replace(' ', '', $address[2]);
            if ($countryName == "USA") {
                $countryID = DB::table("countries")->where('iso3', $countryName)->first();
                if ($countryID != null) {
                    $country_id = $countryID->id;
                    $stateID = DB::table("states")->where('state_code', substr($address[1], 1, 2))->where('country_id', $countryID->id)->first();
                    if ($stateID != null) {
                        $state_id = $stateID->id;
                        $cityID = DB::table("cities")->where('name', $address[0])->where('state_id', $stateID->id)->where('country_id', $countryID->id)->first();
                        if ($cityID == null) {
                            $c_id = DB::table("cities")->insertGetId([
                                'name' => $address[0],
                                'state_id' => $stateID->id,
                                'state_code' => $stateID->state_code,
                                'country_id' => $countryID->id,
                                'country_code' => $countryID->iso2,
                                'latitude' => $data->results[0]->geometry->location->lat,
                                'longitude' => $data->results[0]->geometry->location->lng,
                                'flag' => 1,
                            ]);
                            $city_id = $c_id;
                        } else {
                            $city_id = $cityID->id;
                        }
                    }
                }
            }
            return response()->json(array('country_id' => $country_id, 'state_id' => $state_id, 'city_id' => $city_id), 200);
        } else {
            return response()->json(array('country_id' => $country_id, 'state_id' => $state_id, 'city_id' => $city_id), 200);
        }
    }
}
