<?php

namespace SQL\Types;

enum Operation
{
  case INSERT;
  case SELECT;
  case UPDATE;
  case DELETE;
}
