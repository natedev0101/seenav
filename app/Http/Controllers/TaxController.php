<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tax\{
    TaxCompanyModel,
    TaxAdministratorModel,
    TaxTaxModel,
    TaxPlateModel,
    TaxInteriorModel,
    TaxEmployeeModel
};

class TaxController extends Controller
{
    public function init() {
        $companyData = TaxCompanyModel::all();

        $weekData = $companyData->filter(function($data) {
            return $data->type === 'week';
        });

        if ($companyData) {
            return view('subdivisions.tax', ['weekData' => $weekData]);
        }
    }

    public function index() {
        $companyData = TaxCompanyModel::all();
        $adminData = TaxAdministratorModel::where('status', '!=', 'archived')->get();

        $weekData = $companyData->filter(function($data) {
            return $data->type === 'week';
        });

        $monthData = $companyData->filter(function($data) {
            return $data->type === 'month';
        });

        return view('subdivisions.tax', [
            'weekData' => $weekData,
            'monthData' => $monthData,
            'adminData' => $adminData,
            'activeTab' => session('activeTab', 'week')
        ]);
    }

    public function handler($type) {
        $companyData = TaxCompanyModel::all();

        switch ($type) {
            case 'week':
                $weekData = $companyData->filter(function($data) {
                    return $data->type === 'week';
                });

                session(['activeTab' => 'week']);

                if ($weekData) {
                    return view('subdivisions.tax', ['weekData' => $weekData, 'activeTab' => 'week']); 
                }
            case 'month':
                $monthData = $companyData->filter(function($data) {
                    return $data->type === 'month';
                });

                session(['activeTab' => 'month']);

                if ($monthData) {
                    return view('subdivisions.tax', ['monthData' => $monthData, 'activeTab' => 'month']); 
                }
            case 'administrator':
                $adminData = TaxAdministratorModel::where('status', '!=', 'archived')->get();

                session(['activeTab' => 'admin']);

                if ($adminData) {
                    return view('subdivisions.tax', ['adminData' => $adminData, 'activeTab' => 'admin']);
                }
            case 'data':

            default: 
                abort(404);
        }
    }
}
