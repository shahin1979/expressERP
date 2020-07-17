<?php

use Illuminate\Database\Seeder;
use App\Models\Common\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::query()->create( [
            'id'=>1,
            'short_code'=>'AD',
            'country_code'=>'020',
            'country_name'=>'Andorra',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>2,
            'short_code'=>'AE',
            'country_code'=>'784',
            'country_name'=>'United Arab Emirates',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AED',
            'currency'=>'United Arab Emirates Dirham',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>3,
            'short_code'=>'AF',
            'country_code'=>'004',
            'country_name'=>'Afghanistan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AFN',
            'currency'=>'Afghanistan Afghani',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>4,
            'short_code'=>'AG',
            'country_code'=>'028',
            'country_name'=>'Antigua and Barbuda',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XCD',
            'currency'=>'East Caribbean Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>5,
            'short_code'=>'AI',
            'country_code'=>'660',
            'country_name'=>'Anguilla',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XCD',
            'currency'=>'East Caribbean Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>6,
            'short_code'=>'AL',
            'country_code'=>'008',
            'country_name'=>'Albania',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ALL',
            'currency'=>'Albanian Lek',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>7,
            'short_code'=>'AM',
            'country_code'=>'051',
            'country_name'=>'Armenia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AMD',
            'currency'=>'Armenian Dram',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>8,
            'short_code'=>'AO',
            'country_code'=>'024',
            'country_name'=>'Angola',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AOA',
            'currency'=>'Angolan Kwanza',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>9,
            'short_code'=>'AQ',
            'country_code'=>'010',
            'country_name'=>'Antarctica',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>NULL,
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>10,
            'short_code'=>'AR',
            'country_code'=>'032',
            'country_name'=>'Argentina',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ARS',
            'currency'=>'Argentine Peso',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>11,
            'short_code'=>'AS',
            'country_code'=>'016',
            'country_name'=>'American Samoa',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>12,
            'short_code'=>'AT',
            'country_code'=>'040',
            'country_name'=>'Austria',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>13,
            'short_code'=>'AU',
            'country_code'=>'036',
            'country_name'=>'Australia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AUD',
            'currency'=>'Australian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>14,
            'short_code'=>'AW',
            'country_code'=>'533',
            'country_name'=>'Aruba',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AWG',
            'currency'=>'Aruban Florin',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>15,
            'short_code'=>'AX',
            'country_code'=>'248',
            'country_name'=>'Åland',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>16,
            'short_code'=>'AZ',
            'country_code'=>'031',
            'country_name'=>'Azerbaijan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AZN',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>17,
            'short_code'=>'BA',
            'country_code'=>'070',
            'country_name'=>'Bosnia and Herzegovina',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BAM',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>18,
            'short_code'=>'BB',
            'country_code'=>'052',
            'country_name'=>'Barbados',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BBD',
            'currency'=>'Barbados Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>19,
            'short_code'=>'BD',
            'country_code'=>'050',
            'country_name'=>'Bangladesh',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BDT',
            'currency'=>'Bangladeshi Taka',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>20,
            'short_code'=>'BE',
            'country_code'=>'056',
            'country_name'=>'Belgium',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>21,
            'short_code'=>'BF',
            'country_code'=>'854',
            'country_name'=>'Burkina Faso',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XOF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>22,
            'short_code'=>'BG',
            'country_code'=>'100',
            'country_name'=>'Bulgaria',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BGN',
            'currency'=>'Bulgarian Lev',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>23,
            'short_code'=>'BH',
            'country_code'=>'048',
            'country_name'=>'Bahrain',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BHD',
            'currency'=>'Bahraini Dinar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>24,
            'short_code'=>'BI',
            'country_code'=>'108',
            'country_name'=>'Burundi',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BIF',
            'currency'=>'Burundi Franc',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>25,
            'short_code'=>'BJ',
            'country_code'=>'204',
            'country_name'=>'Benin',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XOF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>26,
            'short_code'=>'BL',
            'country_code'=>'652',
            'country_name'=>'Saint Barthélemy',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>27,
            'short_code'=>'BM',
            'country_code'=>'060',
            'country_name'=>'Bermuda',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BMD',
            'currency'=>'Bermudian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>28,
            'short_code'=>'BN',
            'country_code'=>'096',
            'country_name'=>'Brunei',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BND',
            'currency'=>'Brunei Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>29,
            'short_code'=>'BO',
            'country_code'=>'068',
            'country_name'=>'Bolivia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BOB',
            'currency'=>'Bolivian Boliviano',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>30,
            'short_code'=>'BQ',
            'country_code'=>'535',
            'country_name'=>'Bonaire',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>31,
            'short_code'=>'BR',
            'country_code'=>'076',
            'country_name'=>'Brazil',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BRL',
            'currency'=>'Brazilian Real',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>32,
            'short_code'=>'BS',
            'country_code'=>'044',
            'country_name'=>'Bahamas',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BSD',
            'currency'=>'Bahamian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>33,
            'short_code'=>'BT',
            'country_code'=>'064',
            'country_name'=>'Bhutan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BTN',
            'currency'=>'Bhutan Ngultrum',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>34,
            'short_code'=>'BV',
            'country_code'=>'074',
            'country_name'=>'Bouvet Island',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NOK',
            'currency'=>'Norwegian Kroner',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>35,
            'short_code'=>'BW',
            'country_code'=>'072',
            'country_name'=>'Botswana',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BWP',
            'currency'=>'Botswanian Pula',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>36,
            'short_code'=>'BY',
            'country_code'=>'112',
            'country_name'=>'Belarus',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BYR',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>37,
            'short_code'=>'BZ',
            'country_code'=>'084',
            'country_name'=>'Belize',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'BZD',
            'currency'=>'Belize Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>38,
            'short_code'=>'CA',
            'country_code'=>'124',
            'country_name'=>'Canada',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CAD',
            'currency'=>'Canadian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>39,
            'short_code'=>'CC',
            'country_code'=>'166',
            'country_name'=>'Cocos [Keeling] Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AUD',
            'currency'=>'Australian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>40,
            'short_code'=>'CD',
            'country_code'=>'180',
            'country_name'=>'Democratic Republic of the Congo',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CDF',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>41,
            'short_code'=>'CF',
            'country_code'=>'140',
            'country_name'=>'Central African Republic',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XAF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>42,
            'short_code'=>'CG',
            'country_code'=>'178',
            'country_name'=>'Republic of the Congo',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XAF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>43,
            'short_code'=>'CH',
            'country_code'=>'756',
            'country_name'=>'Switzerland',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CHF',
            'currency'=>'Swiss Franc',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>44,
            'short_code'=>'CI',
            'country_code'=>'384',
            'country_name'=>'Ivory Coast',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XOF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>45,
            'short_code'=>'CK',
            'country_code'=>'184',
            'country_name'=>'Cook Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NZD',
            'currency'=>'New Zealand Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>46,
            'short_code'=>'CL',
            'country_code'=>'152',
            'country_name'=>'Chile',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CLP',
            'currency'=>'Chilean Peso',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>47,
            'short_code'=>'CM',
            'country_code'=>'120',
            'country_name'=>'Cameroon',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XAF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>48,
            'short_code'=>'CN',
            'country_code'=>'156',
            'country_name'=>'China',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CNY',
            'currency'=>'Yuan Renminbi',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>49,
            'short_code'=>'CO',
            'country_code'=>'170',
            'country_name'=>'Colombia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'COP',
            'currency'=>'Colombian Peso',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>50,
            'short_code'=>'CR',
            'country_code'=>'188',
            'country_name'=>'Costa Rica',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CRC',
            'currency'=>'Costa Rican Colon',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>51,
            'short_code'=>'CU',
            'country_code'=>'192',
            'country_name'=>'Cuba',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CUP',
            'currency'=>'Cuban Peso',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>52,
            'short_code'=>'CV',
            'country_code'=>'132',
            'country_name'=>'Cape Verde',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CVE',
            'currency'=>'Cape Verde Escudo',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>53,
            'short_code'=>'CW',
            'country_code'=>'531',
            'country_name'=>'Curacao',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ANG',
            'currency'=>'Netherlands Antillian Guilder',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>54,
            'short_code'=>'CX',
            'country_code'=>'162',
            'country_name'=>'Christmas Island',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AUD',
            'currency'=>'Australian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>55,
            'short_code'=>'CY',
            'country_code'=>'196',
            'country_name'=>'Cyprus',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>56,
            'short_code'=>'CZ',
            'country_code'=>'203',
            'country_name'=>'Czechia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CZK',
            'currency'=>'Czech Republic Koruna',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>57,
            'short_code'=>'DE',
            'country_code'=>'276',
            'country_name'=>'Germany',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>58,
            'short_code'=>'DJ',
            'country_code'=>'262',
            'country_name'=>'Djibouti',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'DJF',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>59,
            'short_code'=>'DK',
            'country_code'=>'208',
            'country_name'=>'Denmark',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'DKK',
            'currency'=>'Danish Krone',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>60,
            'short_code'=>'DM',
            'country_code'=>'212',
            'country_name'=>'Dominica',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XCD',
            'currency'=>'East Caribbean Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>61,
            'short_code'=>'DO',
            'country_code'=>'214',
            'country_name'=>'Dominican Republic',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'DOP',
            'currency'=>'Dominican Peso',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>62,
            'short_code'=>'DZ',
            'country_code'=>'012',
            'country_name'=>'Algeria',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'DZD',
            'currency'=>'Algerian Dinar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>63,
            'short_code'=>'EC',
            'country_code'=>'218',
            'country_name'=>'Ecuador',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>64,
            'short_code'=>'EE',
            'country_code'=>'233',
            'country_name'=>'Estonia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>65,
            'short_code'=>'EG',
            'country_code'=>'818',
            'country_name'=>'Egypt',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EGP',
            'currency'=>'Egyptian Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>66,
            'short_code'=>'EH',
            'country_code'=>'732',
            'country_name'=>'Western Sahara',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MAD',
            'currency'=>'Moroccan Dirham',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>67,
            'short_code'=>'ER',
            'country_code'=>'232',
            'country_name'=>'Eritrea',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ERN',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>68,
            'short_code'=>'ES',
            'country_code'=>'724',
            'country_name'=>'Spain',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>69,
            'short_code'=>'ET',
            'country_code'=>'231',
            'country_name'=>'Ethiopia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ETB',
            'currency'=>'Ethiopian Birr',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>70,
            'short_code'=>'FI',
            'country_code'=>'246',
            'country_name'=>'Finland',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>71,
            'short_code'=>'FJ',
            'country_code'=>'242',
            'country_name'=>'Fiji',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'FJD',
            'currency'=>'Fiji Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>72,
            'short_code'=>'FK',
            'country_code'=>'238',
            'country_name'=>'Falkland Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'FKP',
            'currency'=>'Falkland Islands Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>73,
            'short_code'=>'FM',
            'country_code'=>'583',
            'country_name'=>'Micronesia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>74,
            'short_code'=>'FO',
            'country_code'=>'234',
            'country_name'=>'Faroe Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'DKK',
            'currency'=>'Danish Krone',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>75,
            'short_code'=>'FR',
            'country_code'=>'250',
            'country_name'=>'France',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>76,
            'short_code'=>'GA',
            'country_code'=>'266',
            'country_name'=>'Gabon',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XAF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>77,
            'short_code'=>'GB',
            'country_code'=>'826',
            'country_name'=>'United Kingdom',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GBP',
            'currency'=>'British Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>78,
            'short_code'=>'GD',
            'country_code'=>'308',
            'country_name'=>'Grenada',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XCD',
            'currency'=>'East Caribbean Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>79,
            'short_code'=>'GE',
            'country_code'=>'268',
            'country_name'=>'Georgia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GEL',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>80,
            'short_code'=>'GF',
            'country_code'=>'254',
            'country_name'=>'French Guiana',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>81,
            'short_code'=>'GG',
            'country_code'=>'831',
            'country_name'=>'Guernsey',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GBP',
            'currency'=>'British Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>82,
            'short_code'=>'GH',
            'country_code'=>'288',
            'country_name'=>'Ghana',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GHS',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>83,
            'short_code'=>'GI',
            'country_code'=>'292',
            'country_name'=>'Gibraltar',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GIP',
            'currency'=>'Gibraltar Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>84,
            'short_code'=>'GL',
            'country_code'=>'304',
            'country_name'=>'Greenland',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'DKK',
            'currency'=>'Danish Krone',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>85,
            'short_code'=>'GM',
            'country_code'=>'270',
            'country_name'=>'Gambia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GMD',
            'currency'=>'Gambian Dalasi',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>86,
            'short_code'=>'GN',
            'country_code'=>'324',
            'country_name'=>'Guinea',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GNF',
            'currency'=>'Guinea Franc',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>87,
            'short_code'=>'GP',
            'country_code'=>'312',
            'country_name'=>'Guadeloupe',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>88,
            'short_code'=>'GQ',
            'country_code'=>'226',
            'country_name'=>'Equatorial Guinea',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XAF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>89,
            'short_code'=>'GR',
            'country_code'=>'300',
            'country_name'=>'Greece',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>90,
            'short_code'=>'GS',
            'country_code'=>'239',
            'country_name'=>'South Georgia and the South Sandwich Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GBP',
            'currency'=>'British Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>91,
            'short_code'=>'GT',
            'country_code'=>'320',
            'country_name'=>'Guatemala',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GTQ',
            'currency'=>'Guatemalan Quetzal',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>92,
            'short_code'=>'GU',
            'country_code'=>'316',
            'country_name'=>'Guam',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>93,
            'short_code'=>'GW',
            'country_code'=>'624',
            'country_name'=>'Guinea-Bissau',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XOF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>94,
            'short_code'=>'GY',
            'country_code'=>'328',
            'country_name'=>'Guyana',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GYD',
            'currency'=>'Guyanan Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>95,
            'short_code'=>'HK',
            'country_code'=>'344',
            'country_name'=>'Hong Kong',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'HKD',
            'currency'=>'Hong Kong Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>96,
            'short_code'=>'HM',
            'country_code'=>'334',
            'country_name'=>'Heard Island and McDonald Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AUD',
            'currency'=>'Australian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>97,
            'short_code'=>'HN',
            'country_code'=>'340',
            'country_name'=>'Honduras',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'HNL',
            'currency'=>'Honduran Lempira',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>98,
            'short_code'=>'HR',
            'country_code'=>'191',
            'country_name'=>'Croatia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'HRK',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>99,
            'short_code'=>'HT',
            'country_code'=>'332',
            'country_name'=>'Haiti',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'HTG',
            'currency'=>'Haitian Gourde',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>100,
            'short_code'=>'HU',
            'country_code'=>'348',
            'country_name'=>'Hungary',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'HUF',
            'currency'=>'Hungarian Forint',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>101,
            'short_code'=>'ID',
            'country_code'=>'360',
            'country_name'=>'Indonesia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'IDR',
            'currency'=>'Indonesian Rupiah',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>102,
            'short_code'=>'IE',
            'country_code'=>'372',
            'country_name'=>'Ireland',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>103,
            'short_code'=>'IL',
            'country_code'=>'376',
            'country_name'=>'Israel',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ILS',
            'currency'=>'Israeli Shekel',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>104,
            'short_code'=>'IM',
            'country_code'=>'833',
            'country_name'=>'Isle of Man',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GBP',
            'currency'=>'British Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>105,
            'short_code'=>'IN',
            'country_code'=>'356',
            'country_name'=>'India',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'INR',
            'currency'=>'Indian Rupee',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>106,
            'short_code'=>'IO',
            'country_code'=>'086',
            'country_name'=>'British Indian Ocean Territory',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>107,
            'short_code'=>'IQ',
            'country_code'=>'368',
            'country_name'=>'Iraq',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'IQD',
            'currency'=>'Iraqi Dinar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>108,
            'short_code'=>'IR',
            'country_code'=>'364',
            'country_name'=>'Iran',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'IRR',
            'currency'=>'Iranian Rial',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>109,
            'short_code'=>'IS',
            'country_code'=>'352',
            'country_name'=>'Iceland',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ISK',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>110,
            'short_code'=>'IT',
            'country_code'=>'380',
            'country_name'=>'Italy',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>111,
            'short_code'=>'JE',
            'country_code'=>'832',
            'country_name'=>'Jersey',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'GBP',
            'currency'=>'British Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>112,
            'short_code'=>'JM',
            'country_code'=>'388',
            'country_name'=>'Jamaica',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'JMD',
            'currency'=>'Jamaican Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>113,
            'short_code'=>'JO',
            'country_code'=>'400',
            'country_name'=>'Jordan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'JOD',
            'currency'=>'Jordanian Dinar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>114,
            'short_code'=>'JP',
            'country_code'=>'392',
            'country_name'=>'Japan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'JPY',
            'currency'=>'Japanese Yen',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>115,
            'short_code'=>'KE',
            'country_code'=>'404',
            'country_name'=>'Kenya',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KES',
            'currency'=>'Kenyan Schilling',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>116,
            'short_code'=>'KG',
            'country_code'=>'417',
            'country_name'=>'Kyrgyzstan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KGS',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>117,
            'short_code'=>'KH',
            'country_code'=>'116',
            'country_name'=>'Cambodia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KHR',
            'currency'=>'Kampuchean (Cambodian) Riel',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>118,
            'short_code'=>'KI',
            'country_code'=>'296',
            'country_name'=>'Kiribati',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AUD',
            'currency'=>'Australian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>119,
            'short_code'=>'KM',
            'country_code'=>'174',
            'country_name'=>'Comoros',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KMF',
            'currency'=>'Comoros Franc',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>120,
            'short_code'=>'KN',
            'country_code'=>'659',
            'country_name'=>'Saint Kitts and Nevis',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XCD',
            'currency'=>'East Caribbean Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>121,
            'short_code'=>'KP',
            'country_code'=>'408',
            'country_name'=>'North Korea',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KPW',
            'currency'=>'North Korean Won',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>122,
            'short_code'=>'KR',
            'country_code'=>'410',
            'country_name'=>'South Korea',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KRW',
            'currency'=>'South Korean Won',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>123,
            'short_code'=>'KW',
            'country_code'=>'414',
            'country_name'=>'Kuwait',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KWD',
            'currency'=>'Kuwaiti Dinar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>124,
            'short_code'=>'KY',
            'country_code'=>'136',
            'country_name'=>'Cayman Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KYD',
            'currency'=>'Cayman Islands Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>125,
            'short_code'=>'KZ',
            'country_code'=>'398',
            'country_name'=>'Kazakhstan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'KZT',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>126,
            'short_code'=>'LA',
            'country_code'=>'418',
            'country_name'=>'Laos',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'LAK',
            'currency'=>'Lao Kip',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>127,
            'short_code'=>'LB',
            'country_code'=>'422',
            'country_name'=>'Lebanon',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'LBP',
            'currency'=>'Lebanese Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>128,
            'short_code'=>'LC',
            'country_code'=>'662',
            'country_name'=>'Saint Lucia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XCD',
            'currency'=>'East Caribbean Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>129,
            'short_code'=>'LI',
            'country_code'=>'438',
            'country_name'=>'Liechtenstein',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'CHF',
            'currency'=>'Swiss Franc',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>130,
            'short_code'=>'LK',
            'country_code'=>'144',
            'country_name'=>'Sri Lanka',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'LKR',
            'currency'=>'Sri Lanka Rupee',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>131,
            'short_code'=>'LR',
            'country_code'=>'430',
            'country_name'=>'Liberia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'LRD',
            'currency'=>'Liberian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>132,
            'short_code'=>'LS',
            'country_code'=>'426',
            'country_name'=>'Lesotho',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'LSL',
            'currency'=>'Lesotho Loti',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>133,
            'short_code'=>'LT',
            'country_code'=>'440',
            'country_name'=>'Lithuania',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>134,
            'short_code'=>'LU',
            'country_code'=>'442',
            'country_name'=>'Luxembourg',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>135,
            'short_code'=>'LV',
            'country_code'=>'428',
            'country_name'=>'Latvia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>136,
            'short_code'=>'LY',
            'country_code'=>'434',
            'country_name'=>'Libya',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'LYD',
            'currency'=>'Libyan Dinar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>137,
            'short_code'=>'MA',
            'country_code'=>'504',
            'country_name'=>'Morocco',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MAD',
            'currency'=>'Moroccan Dirham',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>138,
            'short_code'=>'MC',
            'country_code'=>'492',
            'country_name'=>'Monaco',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>139,
            'short_code'=>'MD',
            'country_code'=>'498',
            'country_name'=>'Moldova',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MDL',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>140,
            'short_code'=>'ME',
            'country_code'=>'499',
            'country_name'=>'Montenegro',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>141,
            'short_code'=>'MF',
            'country_code'=>'663',
            'country_name'=>'Saint Martin',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>142,
            'short_code'=>'MG',
            'country_code'=>'450',
            'country_name'=>'Madagascar',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MGA',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>143,
            'short_code'=>'MH',
            'country_code'=>'584',
            'country_name'=>'Marshall Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>144,
            'short_code'=>'MK',
            'country_code'=>'807',
            'country_name'=>'Macedonia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MKD',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>145,
            'short_code'=>'ML',
            'country_code'=>'466',
            'country_name'=>'Mali',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XOF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>146,
            'short_code'=>'MM',
            'country_code'=>'104',
            'country_name'=>'Myanmar [Burma]',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MMK',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>147,
            'short_code'=>'MN',
            'country_code'=>'496',
            'country_name'=>'Mongolia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MNT',
            'currency'=>'Mongolian Tugrik',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>148,
            'short_code'=>'MO',
            'country_code'=>'446',
            'country_name'=>'Macao',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MOP',
            'currency'=>'Macau Pataca',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>149,
            'short_code'=>'MP',
            'country_code'=>'580',
            'country_name'=>'Northern Mariana Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>150,
            'short_code'=>'MQ',
            'country_code'=>'474',
            'country_name'=>'Martinique',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>151,
            'short_code'=>'MR',
            'country_code'=>'478',
            'country_name'=>'Mauritania',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MRO',
            'currency'=>'Mauritanian Ouguiya',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>152,
            'short_code'=>'MS',
            'country_code'=>'500',
            'country_name'=>'Montserrat',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XCD',
            'currency'=>'East Caribbean Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>153,
            'short_code'=>'MT',
            'country_code'=>'470',
            'country_name'=>'Malta',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>154,
            'short_code'=>'MU',
            'country_code'=>'480',
            'country_name'=>'Mauritius',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MUR',
            'currency'=>'Mauritius Rupee',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>155,
            'short_code'=>'MV',
            'country_code'=>'462',
            'country_name'=>'Maldives',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MVR',
            'currency'=>'Maldive Rufiyaa',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>156,
            'short_code'=>'MW',
            'country_code'=>'454',
            'country_name'=>'Malawi',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MWK',
            'currency'=>'Malawi Kwacha',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>157,
            'short_code'=>'MX',
            'country_code'=>'484',
            'country_name'=>'Mexico',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MXN',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>158,
            'short_code'=>'MY',
            'country_code'=>'458',
            'country_name'=>'Malaysia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MYR',
            'currency'=>'Malaysian Ringgit',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>159,
            'short_code'=>'MZ',
            'country_code'=>'508',
            'country_name'=>'Mozambique',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'MZN',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>160,
            'short_code'=>'NA',
            'country_code'=>'516',
            'country_name'=>'Namibia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NAD',
            'currency'=>'Namibian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>161,
            'short_code'=>'NC',
            'country_code'=>'540',
            'country_name'=>'New Caledonia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XPF',
            'currency'=>'Comptoirs FranÃ§ais du Pacifique Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>162,
            'short_code'=>'NE',
            'country_code'=>'562',
            'country_name'=>'Niger',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XOF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>163,
            'short_code'=>'NF',
            'country_code'=>'574',
            'country_name'=>'Norfolk Island',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AUD',
            'currency'=>'Australian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>164,
            'short_code'=>'NG',
            'country_code'=>'566',
            'country_name'=>'Nigeria',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NGN',
            'currency'=>'Nigerian Naira',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>165,
            'short_code'=>'NI',
            'country_code'=>'558',
            'country_name'=>'Nicaragua',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NIO',
            'currency'=>'Nicaraguan Cordoba',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>166,
            'short_code'=>'NL',
            'country_code'=>'528',
            'country_name'=>'Netherlands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>167,
            'short_code'=>'NO',
            'country_code'=>'578',
            'country_name'=>'Norway',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NOK',
            'currency'=>'Norwegian Kroner',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>168,
            'short_code'=>'NP',
            'country_code'=>'524',
            'country_name'=>'Nepal',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NPR',
            'currency'=>'Nepalese Rupee',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>169,
            'short_code'=>'NR',
            'country_code'=>'520',
            'country_name'=>'Nauru',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AUD',
            'currency'=>'Australian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>170,
            'short_code'=>'NU',
            'country_code'=>'570',
            'country_name'=>'Niue',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NZD',
            'currency'=>'New Zealand Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>171,
            'short_code'=>'NZ',
            'country_code'=>'554',
            'country_name'=>'New Zealand',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NZD',
            'currency'=>'New Zealand Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>172,
            'short_code'=>'OM',
            'country_code'=>'512',
            'country_name'=>'Oman',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'OMR',
            'currency'=>'Omani Rial',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>173,
            'short_code'=>'PA',
            'country_code'=>'591',
            'country_name'=>'Panama',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'PAB',
            'currency'=>'Panamanian Balboa',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>174,
            'short_code'=>'PE',
            'country_code'=>'604',
            'country_name'=>'Peru',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'PEN',
            'currency'=>'Peruvian Nuevo Sol',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>175,
            'short_code'=>'PF',
            'country_code'=>'258',
            'country_name'=>'French Polynesia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XPF',
            'currency'=>'Comptoirs FranÃ§ais du Pacifique Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>176,
            'short_code'=>'PG',
            'country_code'=>'598',
            'country_name'=>'Papua New Guinea',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'PGK',
            'currency'=>'Papua New Guinea Kina',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>177,
            'short_code'=>'PH',
            'country_code'=>'608',
            'country_name'=>'Philippines',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'PHP',
            'currency'=>'Philippine Peso',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>178,
            'short_code'=>'PK',
            'country_code'=>'586',
            'country_name'=>'Pakistan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'PKR',
            'currency'=>'Pakistan Rupee',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>179,
            'short_code'=>'PL',
            'country_code'=>'616',
            'country_name'=>'Poland',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'PLN',
            'currency'=>'Polish Zloty',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>180,
            'short_code'=>'PM',
            'country_code'=>'666',
            'country_name'=>'Saint Pierre and Miquelon',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>181,
            'short_code'=>'PN',
            'country_code'=>'612',
            'country_name'=>'Pitcairn Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NZD',
            'currency'=>'New Zealand Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>182,
            'short_code'=>'PR',
            'country_code'=>'630',
            'country_name'=>'Puerto Rico',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>183,
            'short_code'=>'PS',
            'country_code'=>'275',
            'country_name'=>'Palestine',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ILS',
            'currency'=>'Israeli Shekel',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>184,
            'short_code'=>'PT',
            'country_code'=>'620',
            'country_name'=>'Portugal',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>185,
            'short_code'=>'PW',
            'country_code'=>'585',
            'country_name'=>'Palau',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>186,
            'short_code'=>'PY',
            'country_code'=>'600',
            'country_name'=>'Paraguay',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'PYG',
            'currency'=>'Paraguay Guarani',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>187,
            'short_code'=>'QA',
            'country_code'=>'634',
            'country_name'=>'Qatar',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'QAR',
            'currency'=>'Qatari Rial',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>188,
            'short_code'=>'RE',
            'country_code'=>'638',
            'country_name'=>'Réunion',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>189,
            'short_code'=>'RO',
            'country_code'=>'642',
            'country_name'=>'Romania',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'RON',
            'currency'=>'Romanian Leu',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>190,
            'short_code'=>'RS',
            'country_code'=>'688',
            'country_name'=>'Serbia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'RSD',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>191,
            'short_code'=>'RU',
            'country_code'=>'643',
            'country_name'=>'Russia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'RUB',
            'currency'=>'Russian Ruble',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>192,
            'short_code'=>'RW',
            'country_code'=>'646',
            'country_name'=>'Rwanda',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'RWF',
            'currency'=>'Rwanda Franc',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>193,
            'short_code'=>'SA',
            'country_code'=>'682',
            'country_name'=>'Saudi Arabia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SAR',
            'currency'=>'Saudi Arabian Riyal',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>194,
            'short_code'=>'SB',
            'country_code'=>'090',
            'country_name'=>'Solomon Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SBD',
            'currency'=>'Solomon Islands Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>195,
            'short_code'=>'SC',
            'country_code'=>'690',
            'country_name'=>'Seychelles',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SCR',
            'currency'=>'Seychelles Rupee',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>196,
            'short_code'=>'SD',
            'country_code'=>'729',
            'country_name'=>'Sudan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SDG',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>197,
            'short_code'=>'SE',
            'country_code'=>'752',
            'country_name'=>'Sweden',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SEK',
            'currency'=>'Swedish Krona',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>198,
            'short_code'=>'SG',
            'country_code'=>'702',
            'country_name'=>'Singapore',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SGD',
            'currency'=>'Singapore Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>199,
            'short_code'=>'SH',
            'country_code'=>'654',
            'country_name'=>'Saint Helena',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SHP',
            'currency'=>'St. Helena Pound',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>200,
            'short_code'=>'SI',
            'country_code'=>'705',
            'country_name'=>'Slovenia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>201,
            'short_code'=>'SJ',
            'country_code'=>'744',
            'country_name'=>'Svalbard and Jan Mayen',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NOK',
            'currency'=>'Norwegian Kroner',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>202,
            'short_code'=>'SK',
            'country_code'=>'703',
            'country_name'=>'Slovakia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>203,
            'short_code'=>'SL',
            'country_code'=>'694',
            'country_name'=>'Sierra Leone',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SLL',
            'currency'=>'Sierra Leone Leone',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>204,
            'short_code'=>'SM',
            'country_code'=>'674',
            'country_name'=>'San Marino',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>205,
            'short_code'=>'SN',
            'country_code'=>'686',
            'country_name'=>'Senegal',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XOF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>206,
            'short_code'=>'SO',
            'country_code'=>'706',
            'country_name'=>'Somalia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SOS',
            'currency'=>'Somali Schilling',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>207,
            'short_code'=>'SR',
            'country_code'=>'740',
            'country_name'=>'Suriname',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SRD',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>208,
            'short_code'=>'SS',
            'country_code'=>'728',
            'country_name'=>'South Sudan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SSP',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>209,
            'short_code'=>'ST',
            'country_code'=>'678',
            'country_name'=>'São Tomé and Príncipe',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'STD',
            'currency'=>'Sao Tome and Principe Dobra',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>210,
            'short_code'=>'SV',
            'country_code'=>'222',
            'country_name'=>'El Salvador',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>211,
            'short_code'=>'SX',
            'country_code'=>'534',
            'country_name'=>'Sint Maarten',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ANG',
            'currency'=>'Netherlands Antillian Guilder',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>212,
            'short_code'=>'SY',
            'country_code'=>'760',
            'country_name'=>'Syria',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SYP',
            'currency'=>'Syrian Potmd',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>213,
            'short_code'=>'SZ',
            'country_code'=>'748',
            'country_name'=>'Swaziland',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'SZL',
            'currency'=>'Swaziland Lilangeni',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>214,
            'short_code'=>'TC',
            'country_code'=>'796',
            'country_name'=>'Turks and Caicos Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>215,
            'short_code'=>'TD',
            'country_code'=>'148',
            'country_name'=>'Chad',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XAF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>216,
            'short_code'=>'TF',
            'country_code'=>'260',
            'country_name'=>'French Southern Territories',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>217,
            'short_code'=>'TG',
            'country_code'=>'768',
            'country_name'=>'Togo',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XOF',
            'currency'=>'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>218,
            'short_code'=>'TH',
            'country_code'=>'764',
            'country_name'=>'Thailand',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'THB',
            'currency'=>'Thai Baht',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>219,
            'short_code'=>'TJ',
            'country_code'=>'762',
            'country_name'=>'Tajikistan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'TJS',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>220,
            'short_code'=>'TK',
            'country_code'=>'772',
            'country_name'=>'Tokelau',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'NZD',
            'currency'=>'New Zealand Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>221,
            'short_code'=>'TL',
            'country_code'=>'626',
            'country_name'=>'East Timor',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>222,
            'short_code'=>'TM',
            'country_code'=>'795',
            'country_name'=>'Turkmenistan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'TMT',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>223,
            'short_code'=>'TN',
            'country_code'=>'788',
            'country_name'=>'Tunisia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'TND',
            'currency'=>'Tunisian Dinar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>224,
            'short_code'=>'TO',
            'country_code'=>'776',
            'country_name'=>'Tonga',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'TOP',
            'currency'=>'Tongan Paanga',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>225,
            'short_code'=>'TR',
            'country_code'=>'792',
            'country_name'=>'Turkey',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'TRY',
            'currency'=>'Turkish Lira',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>226,
            'short_code'=>'TT',
            'country_code'=>'780',
            'country_name'=>'Trinidad and Tobago',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'TTD',
            'currency'=>'Trinidad and Tobago Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>227,
            'short_code'=>'TV',
            'country_code'=>'798',
            'country_name'=>'Tuvalu',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'AUD',
            'currency'=>'Australian Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>228,
            'short_code'=>'TW',
            'country_code'=>'158',
            'country_name'=>'Taiwan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'TWD',
            'currency'=>'Taiwan Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>229,
            'short_code'=>'TZ',
            'country_code'=>'834',
            'country_name'=>'Tanzania',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'TZS',
            'currency'=>'Tanzanian Schilling',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>230,
            'short_code'=>'UA',
            'country_code'=>'804',
            'country_name'=>'Ukraine',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'UAH',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>231,
            'short_code'=>'UG',
            'country_code'=>'800',
            'country_name'=>'Uganda',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'UGX',
            'currency'=>'Uganda Shilling',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>232,
            'short_code'=>'UM',
            'country_code'=>'581',
            'country_name'=>'U.S. Minor Outlying Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>233,
            'short_code'=>'US',
            'country_code'=>'840',
            'country_name'=>'United States',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>234,
            'short_code'=>'UY',
            'country_code'=>'858',
            'country_name'=>'Uruguay',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'UYU',
            'currency'=>'Uruguayan Peso',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>235,
            'short_code'=>'UZ',
            'country_code'=>'860',
            'country_name'=>'Uzbekistan',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'UZS',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>236,
            'short_code'=>'VA',
            'country_code'=>'336',
            'country_name'=>'Vatican City',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>237,
            'short_code'=>'VC',
            'country_code'=>'670',
            'country_name'=>'Saint Vincent and the Grenadines',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XCD',
            'currency'=>'East Caribbean Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>238,
            'short_code'=>'VE',
            'country_code'=>'862',
            'country_name'=>'Venezuela',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'VEF',
            'currency'=>'Venezualan Bolivar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>239,
            'short_code'=>'VG',
            'country_code'=>'092',
            'country_name'=>'British Virgin Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>240,
            'short_code'=>'VI',
            'country_code'=>'850',
            'country_name'=>'U.S. Virgin Islands',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'USD',
            'currency'=>'US Dollar',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>241,
            'short_code'=>'VN',
            'country_code'=>'704',
            'country_name'=>'Vietnam',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'VND',
            'currency'=>'Vietnamese Dong',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>242,
            'short_code'=>'VU',
            'country_code'=>'548',
            'country_name'=>'Vanuatu',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'VUV',
            'currency'=>'Vanuatu Vatu',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>243,
            'short_code'=>'WF',
            'country_code'=>'876',
            'country_name'=>'Wallis and Futuna',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'XPF',
            'currency'=>'Comptoirs FranÃ§ais du Pacifique Francs',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>244,
            'short_code'=>'WS',
            'country_code'=>'882',
            'country_name'=>'Samoa',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'WST',
            'currency'=>'Samoan Tala',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>245,
            'short_code'=>'XK',
            'country_code'=>'0',
            'country_name'=>'Kosovo',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>246,
            'short_code'=>'YE',
            'country_code'=>'887',
            'country_name'=>'Yemen',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'YER',
            'currency'=>'Yemeni Rial',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>247,
            'short_code'=>'YT',
            'country_code'=>'175',
            'country_name'=>'Mayotte',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'EUR',
            'currency'=>'Euro',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>248,
            'short_code'=>'ZA',
            'country_code'=>'710',
            'country_name'=>'South Africa',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ZAR',
            'currency'=>'South African Rand',
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>249,
            'short_code'=>'ZM',
            'country_code'=>'894',
            'country_name'=>'Zambia',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ZMW',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );



        Country::query()->create( [
            'id'=>250,
            'short_code'=>'ZW',
            'country_code'=>'716',
            'country_name'=>'Zimbabwe',
            'nick_name'=>NULL,
            'currency_code'=>NULL,
            'currency_short'=>'ZWL',
            'currency'=>NULL,
            'currency_symbol'=>NULL,
            'phone_code'=>NULL
        ] );
    }
}
