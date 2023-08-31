@extends('errors::minimal')

@section('title', __('errors.FAILED_PARAMETERS'))
@section('code', '422')
@section('message', $exception->getMessage())
