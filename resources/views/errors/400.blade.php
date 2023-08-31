@extends('errors::minimal')

@section('title', __('errors.UNKNOWN_ERROR'))
@section('code', '400')
@section('message', $exception->getMessage())
