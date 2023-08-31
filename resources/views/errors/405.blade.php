@extends('errors::minimal')

@section('title', __('errors.NOT_SUPPORTED'))
@section('code', '405')
@section('message', $exception->getMessage())
