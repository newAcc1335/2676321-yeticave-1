<?php

enum LotField: string
{
    case TITLE = 'title';
    case CATEGORY = 'category';
    case DESCRIPTION = 'description';
    case STARTING_PRICE = 'starting_price';
    case BID_STEP = 'bid_step';
    case END_TIME = 'end_time';
    case IMAGE = 'image';
}

enum RegisterField: string
{
    case NAME = 'name';
    case EMAIL = 'email';
    case PASSWORD = 'password';
    case CONTACT_INFO = 'contactInfo';
}

enum LoginField: string
{
    case EMAIL = 'email';
    case PASSWORD = 'password';
}

enum BidField: string
{
    case COST = 'cost';
}
